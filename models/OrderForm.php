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
    public $materialId;
    public $length;
    public $width;
    public $count = 1;

    public function rules()
    {
        return [
            [['materialId', 'length', 'width', 'count'], 'required'],
            ['materialId', 'integer'],
            [['length', 'width'], 'integer', 'min' => 100, 'max' => 5000],
            ['count', 'integer', 'min' => 1, 'max' => 3000],
        ];
    }

    public function attributeLabels()
    {
        return [
            'materialId' => 'Материал',
            'length' => 'Длина',
            'width' => 'Ширина',
            'count' => 'Количество',
        ];
    }

    public function saveOrder()
    {
        $material = Material::findOne(['id' => $this->materialId]);
        $order = new Order();
        $order->material_id = $this->materialId;
        $order->user_id = \Yii::$app->user->identity->id;
        $order->price = $material->price_100mm2 * ($this->length * $this->width * $this->count) / 100 * 1.3;
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