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
 * @property int $keyword
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
            [['name', 'keyword'], 'string', 'max' => 255],
            [['img'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, xlsx'],
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
            'keyword' => 'Keywords',
        ];
    }

    public function gerNameProd(){
        return $this->hasOne(Category::className(),['id' => 'category_id']);
    }
}
