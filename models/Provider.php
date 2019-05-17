<?php

namespace app\models;


use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "provider".
 *
 * @property int $id
 * @property string $email
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $price_cut
 * @property string $price_100mm2
 * @property string $address
 * @property string $company_name
 * @property string $company_description
 */
class Provider extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'provider';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'first_name', 'last_name', 'address', 'company_name'], 'required'],
            [['price_cut', 'price_100mm2'], 'number'],
            [['company_description'], 'string'],
            [['email', 'company_name'], 'string', 'max' => 255],
            ['email', 'email'],
            [['first_name', 'middle_name', 'last_name'], 'string', 'max' => 45],
            [['address'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'first_name' => 'Имя',
            'middle_name' => 'Отчество',
            'last_name' => 'Фамилия',
            'price_cut' => 'Цена разреза в рублях',
            'price_100mm2' => 'Цена за 100 кв. мм в рублях',
            'address' => 'Адрес',
            'company_name' => 'Имя компании',
            'company_description' => 'Описание компании',
        ];
    }

    public static function getProviders()
    {
        return ArrayHelper::map(Provider::find()->all(), 'id', 'company_name');
    }

    public function getProviderStock()
    {
        return $this->hasMany(ProviderStock::className(), ['provider_id' => 'id']);
    }
}
