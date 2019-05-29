<?php

namespace app\models;


/**
 * This is the model class for table "provider_stock".
 *
 * @property int $id
 * @property int $material_id
 * @property string $length_mm
 * @property string $width_mm
 * @property int $count
 */
class MaterialStock extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'material_stock';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['material_id', 'length_mm', 'width_mm', 'count'], 'required'],
            [['material_id', 'count'], 'integer'],
            [['length_mm', 'width_mm'], 'number'],
//            [['list_uid'], 'unique'],
//            [['list_uid'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'material_id' => 'Материал',
            'length_mm' => 'Длина',
            'width_mm' => 'Ширина',
            'count' => 'Колличество',
        ];
    }
}
