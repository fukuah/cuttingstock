<?php

/* @var $this yii\web\View */

use \yii\helpers\Html;

$this->title = 'Заказ распила материалов';
?>
<div class="site-index">
    <?php if (Yii::$app->session->hasFlash('orderSuccess')) { ?>
        <div class="alert alert-success">
            Заказ успешно оформлен, информация об изменении статуса заказа будет приходить на адрес вашей электронной
            почты.
        </div>
    <?php } ?>
    <?php if (Yii::$app->session->hasFlash('somethingWentWrong')) { ?>
        <div class="alert alert-success">
            Что-то пошло не так :С пожалуйста свяжитесь с нами и опишите ситуацию, и мы постараемся исправить ошибку.
        </div>
    <?php } ?>
    <?php if (Yii::$app->session->hasFlash('registrationApproved')) { ?>
        <div class="alert alert-success">
            Ваша регистрация на сайте подтверждена, Вы можете войти на сайт, воспользовавшись своим логином и паролем.
        </div>
    <?php } ?>
    <?php if (Yii::$app->session->hasFlash('registrationSuccess')) { ?>
        <div class="alert alert-success">
            На адрес вашей электронной почты отправлено письмо с дальнейшими инструкциями.
        </div>
    <?php } ?>

    <div class="jumbotron">
        <h2>Добро пожаловать!</h2>

        <p class="lead">

        </p>

        <p>
            <?php if (Yii::$app->user->isGuest) {
                echo Html::a('Регистрация', ['/user/registration'], ['class' => 'btn btn-lg btn-success']);
            } else {
                echo Html::a('Сделать заказ', ['/site/order'], ['class' => 'btn btn-lg btn-success']);
            } ?>
        </p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
                    et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                    aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum
                    dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
                    et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                    aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum
                    dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
                    et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                    aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum
                    dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a>
                </p>
            </div>
        </div>

    </div>
</div>
