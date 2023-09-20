<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property int $category_id
 * @property string|null $name
 * @property string $description
 * @property string|null $img
 * @property int $price
 * @property int $cost
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'description', 'price', 'cost'], 'required'],
            [['category_id', 'price', 'cost'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['img'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category Name',
            'name' => 'Name',
            'description' => 'Description',
            'img' => 'Img',
            'price' => 'Price',
            'cost' => 'Cost',
        ];
    }
}
