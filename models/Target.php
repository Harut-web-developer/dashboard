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
            [['store_id'], 'integer'],
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
            'date' => 'Date',
        ];
    }
}