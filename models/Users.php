<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $password
 * @property int $idrole
 * @property int|null $idcompany
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'username', 'password', 'idrole'], 'required'],
            [['idrole', 'idcompany'], 'integer'],
            [['name'], 'string', 'max' => 200],
            [['username', 'password'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idaccount' => 'Idaccount',
            'name' => 'Name',
            'username' => 'Username',
            'password' => 'Password',
            'idrole' => 'Idrole',
            'idcompany' => 'Idcompany',
        ];
    }
}
