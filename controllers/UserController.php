<?php
/**
 * Created by PhpStorm.
 * User: Alexei
 * Date: 12.05.2019
 * Time: 17:33
 */

namespace app\controllers;


use app\models\Order;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Response;
use app\models\LoginForm;
use app\models\RegistrationForm;
use app\models\User;
use Yii;

class UserController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout', 'orders'],
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
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $res = $model->login()) {

            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionRegistration()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new RegistrationForm();
        if ($model->load(Yii::$app->request->post()) && $res = $model->register()) {
            Yii::$app->session->setFlash('registrationSuccess');

            return $this->goBack();
        }

        $model->password = '';
        $model->password_repeat = '';
        return $this->render('registration', [
            'model' => $model,
        ]);
    }

    public function actionApproveRegistration($hash)
    {
        if (User::approveRegistration($hash)) {
            Yii::$app->session->setFlash('registrationApproved');
            return $this->goHome();
        } else {
            Yii::$app->session->setFlash('somethingWentWrong');
            return $this->goHome();
        }
    }

    public function actionOrders()
    {
        $user = Yii::$app->user->identity;

        $orders = Order::findAll(['user_id' => $user->id]);

//        $orderDataProviders = [];
//        foreach ($orders as $order) {
//            $orderDataProviders[] = new ArrayDataProvider([
//                'allModels' => $order
//            ]);
//        }

        return $this->render('orders', [
            'models' => $orders
        ]);
    }
}