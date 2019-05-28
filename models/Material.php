<?php

namespace app\models;


use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "provider".
 *
 * @property int $id
 * @property string $material_name
 * @property string $price_cut
 * @property string $price_100mm2
 * @property integer $count
 * @property float $length_mm
 * @property float $width_mm
 */
class Material extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'material';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price_cut', 'price_100mm2', 'material_name', 'count', 'length_mm', 'width_mm'], 'required'],
            [['price_cut', 'price_100mm2', 'count', 'length_mm', 'width_mm'], 'number'],
            [['material_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'material_name' => 'Материал',
            'price_cut' => 'Цена разреза в рублях',
            'price_100mm2' => 'Цена за 100 кв. мм в рублях',
            'count' => 'Колличество',
            'length_mm' => 'Длина мм',
            'width_mm' => 'Ширина мм'
        ];
    }

    public static function getMaterials()
    {
        return ArrayHelper::map(Material::find()->all(), 'id', 'material_name');
    }

    public function getProviderStock()
    {
        return $this->hasMany(MaterialStock::className(), ['material_id' => 'id']);
    }
}
