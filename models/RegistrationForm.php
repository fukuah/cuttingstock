<?php
/**
 * Created by PhpStorm.
 * User: Alexei
 * Date: 14.05.2019
 * Time: 13:46
 */

namespace app\models;


use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class RegistrationForm extends Model
{

    public $first_name;
    public $middle_name;
    public $last_name;
    public $username;
    public $email;
    public $password;
    public $password_repeat;
    public $password_hash = 'kostyl'; //

    public function rules()
    {
        return ArrayHelper::merge((new User)->rules(), [
                [['password', 'password_repeat'], 'required'],
                [['password', 'password_repeat'], 'string', 'max' => 45],
                [['email'], 'email']
            ]
        );
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge((new User)->attributeLabels(), [
                'password' => 'Пароль',
                'password_repeat' => 'Подтверлите пароль',
            ]
        );
    }

    public function register()
    {
        if ($this->password != $this->password_repeat) {
            return false;
        }

        $user = new User();

        foreach ($this->attributes as $attribute => $value) {
            if ($user->hasAttribute($attribute)) {
                $user->$attribute = $value;
            }
        }

        $user->password_hash = sha1($this->password);
        $user->password_reset_hash = \Yii::$app->security->generateRandomString(45);

        if ($user->save()) {
            \Yii::$app->mailer->compose()
                ->setTo(\Yii::$app->params['adminEmail'])
                ->setFrom([$user->email => $user->getFioShort()])
                ->setReplyTo([$user->email => $user->getFioShort()])
                ->setSubject('Регистрация')
                ->setTextBody('Чтобы подтвердить регистрацию перейдите по ссылке:' . Url::base('http') . Url::to(['/user/approve-registration', 'hash' => $user->password_reset_hash]))
                ->send();
            return true;
        } else {
            print_r($user->errors);
            exit;
        }
    }
}