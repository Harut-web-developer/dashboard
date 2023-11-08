<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_items".
 *
 * @property int $id
 * @property int|null $order_id
 * @property int|null $product_id
 * @property int $quantity
 * @property int $price
 * @property int $revenue
 * @property int $cost
 */
class OrderItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'product_id', 'quantity', 'price', 'revenue', 'cost'], 'integer'],
            [['quantity', 'price', 'revenue', 'cost','product_id'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'product_id' => 'Product ID',
            'quantity' => 'Quantity',
            'price' => 'Price',
            'revenue' => 'Revenue',
            'cost' => 'Cost',
        ];
    }
}
