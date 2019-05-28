<?php

namespace app\models;

use yii\db\ActiveRecord;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    public $authKey;
    public $accessToken;

    const APPROVE_NEEDED = 0;
    const APPROVED = 1;

    public function rules()
    {
        return [
            [['username', 'email', 'first_name', 'last_name', 'password_hash'], 'required'],
            [['username', 'first_name', 'middle_name', 'last_name', 'password_hash'], 'string', 'max' => 45],
            ['email', 'string', 'max' => 90],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'email' => 'Email',
            'first_name' => 'Имя',
            'middle_name' => 'Отчество',
            'last_name' => 'Фамилия',
            'is_admin' => 'Администратор',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }

    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return User::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return User
     */
    public static function findByUsername($username)
    {
        return User::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password_hash === sha1($password);
    }

    public function getFioShort()
    {
        return $this->first_name . ' ' . (isset($this->middle_name) ? $this->middle_name : '') . ' ' . substr($this->last_name, 0, 1) . '.';
    }

    public static function approveRegistration($hash)
    {
        $user = User::findOne(['password_reset_hash' => $hash]);
        $user->status = self::APPROVED;
        $user->password_reset_hash = '';

        return $user->save();
    }
}
