<?php

namespace app\controllers\admin;

use app\models\Material;
use Yii;
use app\models\Order;
use app\models\OrderSearch;
use app\controllers\BaseController;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\logic\Plank;
use app\models\logic\Sheet;
use app\models\OrderStock;
use app\models\MaterialStock;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends BaseController
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (!self::isAdmin()) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (!self::isAdmin()) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->render('view', [
            'model' => $this->findOrder($id),
        ]);
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (!self::isAdmin()) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $model = $this->findOrder($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (!self::isAdmin()) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $this->findOrder($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findOrder($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCutOrders()
    {
        if (!self::isAdmin()) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $allOrderIds = Yii::$app->request->post('selection');
        if (empty($allOrderIds)) {
            Yii::$app->session->setFlash('noOrderSelected');
            return $this->redirect('index');
        }

        $allOrderStocks = OrderStock::findAll(['order_id' => $allOrderIds]);

        $cuttingLists = [];
        $materialIds = [];
        $orderIds = [];
        foreach ($allOrderStocks as $orderStock) {
            for ($i = 0; $i < $orderStock->count; $i++) {
                $cuttingLists[$orderStock->order->material_id][] = new Plank($orderStock->length_mm, $orderStock->width_mm, $orderStock->order_id, $orderStock->order->material_id);
            }
            $materialIds[] = $orderStock->order->material_id;
            $orderIds[] = $orderStock->order->id;

        }
        $materialIds = array_unique($materialIds);
        $orderIds = array_unique($orderIds);
        $materialsAQ = Material::find()->where(['id' => $materialIds]);
        $dropDownMaterials = ArrayHelper::map($materialsAQ->all(), 'id', 'material_name');


        $sheets = [];
        foreach ($materialIds as $materiaId) {

            usort($cuttingLists[$materiaId], function ($a, $b) {
                return $b->width > $a->width;
            });

            $material = $materialsAQ->where(['id' => $materiaId])->one();
            $sheets[$materiaId] = [];
            for ($i = 0; $i < $material->count && count($cuttingLists[$materiaId]) > 0; $i++) {

                $sheets[$materiaId][] = new Sheet($material->length_mm, $material->width_mm, $material->material_name);
                $cuttingLists[$materiaId] = $sheets[$materiaId][$i]->fill($cuttingLists[$materiaId]);
            }
            if (!empty($cuttingLists[$materiaId])) {
                die('Нехватает листов: ' . $material->material_name);
            }
        }

        $sheetsUsed = [];
        $offcuts = [];
        foreach ($sheets as $material => $msheets) {
            $sheetsUsed[$material] = count($msheets);
            foreach ($msheets as $msheet) {
                $offcuts[$material][] = $msheet->offcuts;
            }
        }

        return $this->render('cut-orders', [
            'sheets' => $sheets,
            'sheetsRaw' => $sheets,
            'materials' => $dropDownMaterials,
            'orders' => $orderIds,
            'sheetsUsed' => $sheetsUsed,
            'offcuts' => $offcuts,
        ]);
    }

    public function actionCut()
    {
        $jsonOrders = Yii::$app->request->post('orders');
        $jsonSheetsUsed = Yii::$app->request->post('sheetsUsed');
        $offcutsJson = Yii::$app->request->post('offcuts');

        $orderIds = Json::decode($jsonOrders);
        $sheetsUsed = Json::decode($jsonSheetsUsed);
        $allOffcuts = Json::decode($offcutsJson);


        foreach ($allOffcuts as $material => $sheetOffcuts) {
            foreach ($sheetOffcuts as $offcuts) {
                foreach ($offcuts as $offcut) {
                    $materialStock = new MaterialStock();
                    $materialStock->material_id = $material;
                    $materialStock->length_mm = $offcut[1][0] - $offcut[0][0];
                    $materialStock->width_mm = $offcut[1][1] - $offcut[0][1];
                    $materialStock->count = 1;
                    if (!$materialStock->save()) {
                        print_r($materialStock->errors);
                    }
                }
            }
        }

        $orders = Order::findAll(['id' => $orderIds]);
        foreach ($orders as $order) {
            $order->status = 1;
            $order->served_at = date("Y-m-d H:i:s");
            if (!$order->save()) {
                print_r($order->errors);
            }
        }

        $materials = Material::findAll(['id' => array_keys($sheetsUsed)]);
        foreach ($materials as $material) {
            $material->count -= $sheetsUsed[$material->id];
            if (!$material->save()) {
                print_r($material->errors);
            }
        }


        return $this->redirect('index');
    }
}
