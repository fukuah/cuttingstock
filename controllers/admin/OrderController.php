<?php

namespace app\controllers\admin;

use Yii;
use app\models\Order;
use app\models\OrderSearch;
use app\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\logic\Plank;
use app\models\logic\Sheet;
use app\models\OrderStock;
use app\models\ProviderStock;

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

        $providerStock = ProviderStock::findAll(['provider_id' => [1, 2]]);
        $providerStockItem = $providerStock[1];


        $allOrderStocks = OrderStock::findAll(['order_id' => $allOrderIds]);

        $cuttingList = [];
        foreach ($allOrderStocks as $orderStock) {
            for ($i = 0; $i < $orderStock->count; $i++) {
                $cuttingList[] = new Plank($orderStock->length_mm, $orderStock->width_mm);
            }
        }

        $sheets = [];
        for ($i = 0; $i < $providerStockItem->count; $i++) {
            $sheets[] = new Sheet($providerStockItem->length_mm, $providerStockItem->width_mm);
            $cuttingList = $sheets[$i]->fill($cuttingList);
            if (count($cuttingList) == 0) {
                break;
            }

        }

        return $this->render('cut-orders', [
            'sheets' => $sheets
        ]);
    }
}
