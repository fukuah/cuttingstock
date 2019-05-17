<?php

namespace app\controllers;

use app\models\logic\Plank;
use app\models\logic\Sheet;
use app\models\OrderStock;
use app\models\ProviderStock;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\ContactForm;
use app\models\OrderForm;
use app\models\Provider;
use app\models\Order;
use yii\web\NotFoundHttpException;

class SiteController extends BaseController
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['order'],
                'rules' => [
                    [
                        'actions' => ['logout', 'order', 'admin'],
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
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }


    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionOrder()
    {
        $orderForm = new OrderForm();
        $providerList = Provider::getProviders();

        if ($orderForm->load(Yii::$app->request->post())) {
            Yii::$app->session->setFlash('orderSuccess');

            $orderForm->saveOrder();

            return $this->goHome();
        }

        return $this->render('order', [
            'model' => $orderForm,
            'providerList' => $providerList
        ]);
    }

    public function actionAdmin()
    {
        if (!self::isAdmin()) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $providerStock = ProviderStock::findAll(['provider_id' => [1, 2]]);
        $providerStockItem = $providerStock[1];
//        echo '<pre>';
//        print_r($providerStock);
//        echo '</pre>';

//        foreach ($providerStock as $providerStockItem) {
        $allOrderIds = ArrayHelper::getColumn(Order::findAll(['provider_id' => $providerStockItem->provider_id]), 'id');

        $allOrderStocks = OrderStock::findAll(['order_id' => $allOrderIds]);

        $cuttingList = [];
        foreach ($allOrderStocks as $orderStock) {
            for ($i = 0; $i < $orderStock->count; $i++) {
                $cuttingList[] = new Plank($orderStock->length_mm, $orderStock->width_mm);
            }
        }

        $sheets = array_fill(0, $providerStockItem->count, new Sheet($providerStockItem->length_mm, $providerStockItem->width_mm));

        foreach ($sheets as $sheet) {
            echo 'Sheet: ';
            echo '<pre>';
            print_r($sheet);
            echo '</pre>';
            $cuttingList = $sheet->fill($cuttingList);
            echo '<pre>';
            print_r($sheet->getOffcut());
            echo '</pre>';
            break;
        }

        exit;
//        return $this->render('admin');
    }
}
