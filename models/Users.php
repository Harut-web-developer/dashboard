<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

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
class Users extends ActiveRecord implements IdentityInterface
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

    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool|null if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public static function checkUser($id){
        $user = Users::findOne($id);
        date_default_timezone_set('Asia/Yerevan');
        $datetime_1 = $user->last_login_date;
        $datetime_2 = date('Y-m-d H:i:s');
        $start_datetime = new \DateTime($datetime_1);
        $diff = $start_datetime->diff(new \DateTime($datetime_2));
        $total_minutes = $diff->i;
        if($total_minutes > 10) {
            return false;
        } else {
            return true;
        }
    }

    public static function  checkUserAuthKey($id){
        $user = Users::findOne($id);
        date_default_timezone_set('Asia/Yerevan');
        $datetime_1 = $user->last_login_date;
        $datetime_2 = date('Y-m-d H:i:s');
        $start_datetime = new \DateTime($datetime_1);
        $diff = $start_datetime->diff(new \DateTime($datetime_2));
        $total_hours = $diff->h;
//        var_dump($total_hours);
//        exit();
        if($total_hours > 8) {
            return false;
        } else {
            return true;
        }
    }
}
