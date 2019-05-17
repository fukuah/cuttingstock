<?php

namespace app\models;


/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property int $user_id
 * @property int $provider_id
 * @property int $status
 * @property string $price
 * @property string $created_at
 * @property string $served_at
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'provider_id', 'price'], 'required'],
            [['user_id', 'provider_id', 'status'], 'integer'],
            [['price'], 'number'],
            [['created_at', 'served_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'provider_id' => 'Поставщик',
            'status' => 'Статус выполнения',
            'price' => 'Цена',
            'created_at' => 'Дата заказа',
            'served_at' => 'Дата выполнения',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getProvider()
    {
        return $this->hasOne(Provider::className(), ['id' => 'provider_id']);
    }

    public function getOrderStock()
    {
        return $this->hasMany(OrderStock::className(), ['order_id' => 'id']);
    }
}
