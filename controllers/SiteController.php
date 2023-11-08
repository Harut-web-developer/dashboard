<?php

namespace app\controllers;

use app\models\Users;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\CookieCollection;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */

    public function beforeAction($action)
    {
        $session = Yii::$app->session;

        if ($action->id !== 'login' && !(isset($session['user_id']) && $session['logged'])) {
            return $this->redirect(['site/login']);
        } else if($action->id == 'login' && !(isset($session['user_id']) && $session['logged'])){
            return $this->actionLogin();
        } else if($action->id == 'login') {
            return $this->redirect('/chart');
        }
        return parent::beforeAction($action);
    }
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }



    /**
     * Login action.
     *
     * @return Response|string
     */

    public function actionLogin()
    {
//        echo "<pre>";
        $this->layout = 'loginLayout';
        $model = new LoginForm();

        $session = Yii::$app->session;
        if ($model->load(Yii::$app->request->post())) {
            $uname = Yii::$app->request->post('LoginForm')['username'];
            $pass = Yii::$app->request->post('LoginForm')['password'];
            $remember = Yii::$app->request->post('LoginForm')['rememberMe'];
                if ($remember === '1'){
                    $session->set('remember',true);
                    setcookie('username',$uname,time()+60 * 10,'/');
                    setcookie('password',$pass,time()+60 * 10,'/');
                }
            $hash = sha1($pass);
//                var_dump($hash);
//                exit();
            $identity = Users::findOne(['username' => $uname]);
            if ($identity && $identity->password === $hash) {
                date_default_timezone_set('Asia/Yerevan');
                $identity->last_login_date = date('Y-m-d H:i:s');
                $identity->save(false);
                $session->set('adminRole',$identity->idrole);
                $session->set('user_id',$identity->id);
                $session->set('auth_key',$identity->getAuthKey());
                $session->set('logged',true);
                $session->set('username',$identity->name);
                return $this->redirect('/chart');
            } else {
                $session->set('error', 'User Name / Password is Invalid');
                return $this->redirect('');
            }
        }else{
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return Response
     */

    public function actionErrors(){
        return $this->render('404');
    }

    public function actionLogout()
    {

      session_destroy();
      $this->redirect('/site/login');
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    public function actionUpdateAuthKeys()
    {
       $users = Users::find()->select('id,auth_key,last_login_date')->all();
       $authKeys = Users::find()->select('id,auth_key')->all();

       if (!empty($users)){
           foreach ($users as $user => $simple_user){
               $users__ = Users::findOne($simple_user->id);
               $key = $this->generateRandomString();
               date_default_timezone_set('Asia/Yerevan');
               $datetime_1 = $simple_user->last_login_date;
               $datetime_2 = date('Y-m-d H:i:s');
               $start_datetime = new \DateTime($datetime_1);
               $diff = $start_datetime->diff(new \DateTime($datetime_2));
               if($diff->days > 0) {
                   $total_hours = ((intval($diff->days)) * 8) + $diff->h;
               } else {
                   $total_hours = $diff->h;
               }
               if($total_hours > 8) {
                   $users__->auth_key = $key;
                   foreach ($authKeys as $authKey){
                       if ($authKey->auth_key == $users__->auth_key){
                           $users__->auth_key = $key;
                       }
                   }
                   $users__->save(false);
               }
           }
//           foreach ($authKeys as $key1 => $value1) {
//               foreach ($authKeys as $key2 => $value2) {
//                   if ($key1 < $key2 && $value1->auth_key === $value2->auth_key) {
//                       $value1->auth_key = generateRandomString();
//                       $value1->save(false);
//                   }
//               }
//           }
       }
    }
    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public function actionUpdateDate(){
        $session = Yii::$app->session;
        $post = Yii::$app->request->post();
        if(isset($session['logged'])){
            date_default_timezone_set('Asia/Yerevan');
            $updateDate = Users::findOne(['id' => $session['user_id']]);
            $updateDate->last_login_date = date('Y-m-d H:i:s');
            $updateDate->save(false);
        }
    }
}
?>
