<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property int|null $store_id
 * @property int $quantity
 * @property int $total_price
 * @property string|null $date
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_id', 'quantity', 'total_price'], 'integer'],
            [['quantity', 'total_price'], 'required'],
            [['date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'quantity' => 'Quantity',
            'total_price' => 'Total Price',
            'date' => 'Date',
        ];
    }
}
