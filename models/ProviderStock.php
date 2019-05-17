<?php

namespace app\models;


/**
 * This is the model class for table "provider_stock".
 *
 * @property int $id
 * @property int $provider_id
 * @property string $length_mm
 * @property string $width_mm
 * @property int $count
 */
class ProviderStock extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'provider_stock';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'provider_id', 'length_mm', 'width_mm', 'count'], 'required'],
            [['id', 'provider_id', 'count'], 'integer'],
            [['length_mm', 'width_mm'], 'number'],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'provider_id' => 'Provider ID',
            'length_mm' => 'Length Mm',
            'width_mm' => 'Width Mm',
            'count' => 'Count',
        ];
    }
}
