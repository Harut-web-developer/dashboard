<?php

namespace app\controllers;

use app\models\Users;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
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
        } else {
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

        $this->layout = 'loginLayout';
        $model = new LoginForm();
        $session = Yii::$app->session;
        if ($model->load(Yii::$app->request->post())) {
            $uname = Yii::$app->request->post('LoginForm')['username'];
            $pass = Yii::$app->request->post('LoginForm')['password'];
            $identity = Users::findOne(['username' => $uname]);

            if ($identity && $identity->password === $pass) {
                $session->set('user_id',$identity->id);
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
}
?>
