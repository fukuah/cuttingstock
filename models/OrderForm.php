<?php
/**
 * Created by PhpStorm.
 * User: Alexei
 * Date: 13.05.2019
 * Time: 14:13
 */

namespace app\models;


use yii\base\Model;

class OrderForm extends Model
{
    public $providerId;
    public $length;
    public $width;
    public $count = 1;

    public function rules()
    {
        return [
            [['providerId', 'length', 'width', 'count'], 'required'],
            ['providerId', 'integer'],
            [['length', 'width'], 'integer', 'min' => 100, 'max' => 5000],
            ['count', 'integer', 'min' => 1, 'max' => 3000],
        ];
    }

    public function attributeLabels()
    {
        return [
            'providerId' => 'Поставщик',
            'length' => 'Длина',
            'width' => 'Ширина',
            'count' => 'Количество',
        ];
    }

    public function saveOrder()
    {
        $provider = Provider::findOne(['id' => $this->providerId]);
        $order = new Order();
        $order->provider_id = $this->providerId;
        $order->user_id = \Yii::$app->user->identity->id;
        $order->price = $provider->price_100mm2 * $this->length * $this->width * $this->count / 100 + $provider->price_cut * $this->count * 2; //TODO
        $order->created_at = date("Y-m-d H:i:s");

        if ($order->save()) {
            $orderStock = new OrderStock();
            $orderStock->order_id = $order->id;
            $orderStock->length_mm = $this->length;
            $orderStock->width_mm = $this->width;
            $orderStock->count = $this->count;

            if ($orderStock->save()) {
                return true;
            } else {
                print_r($orderStock->errors);
                exit;
            }
        } else {
            print_r($order->errors);
            exit;
        }

        return false;

    }
}