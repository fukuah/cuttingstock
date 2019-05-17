<?php

namespace app\models;


/**
 * This is the model class for table "order_stock".
 *
 * @property int $id
 * @property int $order_id
 * @property string $length_mm
 * @property string $width_mm
 * @property string $count
 */
class OrderStock extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_stock';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'length_mm', 'width_mm', 'count'], 'required'],
            [['order_id'], 'integer'],
            [['length_mm', 'width_mm'], 'number'],
            [['count'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'ID заказа',
            'length_mm' => 'Длина',
            'width_mm' => 'Ширина',
            'count' => 'В наличии',
        ];
    }
}
