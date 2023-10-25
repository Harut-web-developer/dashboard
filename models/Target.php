<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "target".
 *
 * @property int $id
 * @property int|null $store_id
 * @property string|null $date
 */
class Target extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'target';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_id','target_price'], 'integer'],
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
            'store_id' => 'Store Name',
            'target_price' => 'Target price',
            'date' => 'Date',
        ];
    }

    public  function getStorName(){
        return $this->hasOne(Store::className(), ['id'=>'store_id']);
    }
}
