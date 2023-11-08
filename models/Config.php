<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "config".
 *
 * @property int $id
 * @property int|null $category_id
 * @property int $procent
 */
class Config extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'config';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', ], 'integer'],
            [['procent'], 'number'],
            [['procent'], 'required'],
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
            'procent' => 'Procent',
        ];
    }

    public function getCategoryName(){
        return $this->hasOne(Category::className(), ['id'=>'category_id']);
    }
}
