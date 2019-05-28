<?php
/**
 * Created by PhpStorm.
 * User: Alexei
 * Date: 14.05.2019
 * Time: 16:58
 */

namespace app\controllers;


use yii\web\Controller;

class BaseController extends Controller
{
    public function init()
    {
        parent::init();

        \Yii::$app->user->loginUrl = ['/user/login'];
    }

    public function isAdmin()
    {
        $user = \Yii::$app->user->identity;
        return isset($user->is_admin) ? $user->is_admin : false;
    }
}