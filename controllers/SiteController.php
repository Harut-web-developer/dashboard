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
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

//    public function beforeAction($action)
//    {
//        echo "<pre>";
//        var_dump($action);
//        die;
////        if($action->id =='login')
////        {
////            $this->enableCsrfValidation = false;
////        }
////        //return true;
////        return parent::beforeAction($action);
//
////        if (!Yii::$app->user->isGuest) {
////            $this->redirect(['./']);
////            return false;
////        }
////        return parent::beforeAction($action);
////        if(Yii::$app->user->isGuest) {
////            print("Welcome back Guest!");
////            print("Your id is ".Yii::$app->user->id);
////            die;
////        } else {
////            print("Your id is ".Yii::$app->user->id);
////            die;
////        }
//
//
////        if($action->id =='login')
////        {
////            $this->enableCsrfValidation = false;
////        }
////        //return true;
////        return parent::beforeAction($action);
//    }
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
//        function loginval($value){
//            if ($value){
//                return true;
//            } else{
//                return false;
//            }
//        }
        $this->layout = 'loginLayout';
        $model = new LoginForm();
        $session = Yii::$app->session;
        if ($model->load(Yii::$app->request->post())) {
            $uname = Yii::$app->request->post('LoginForm')['username'];
            $pass = Yii::$app->request->post('LoginForm')['password'];
            $query = Users::find()
                ->select('username, password, id')
                ->where(['username' => $uname, 'password' => $pass])
                ->asArray()
                ->all();
            if (count($query) === 1) {
                if ($query[0]['username'] === $uname && $query[0]['password'] === $pass) {
                    $_SESSION['username'] = $query[0]['username'];
//                    loginval($query[0]['username']);
                    return $this->redirect('/chart');
                } else {
                    $session->set('error', 'User Name / Password is Invalid');
                    return $this->redirect('');
                }
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
        Yii::$app->user->logout();

        return $this->goHome();

//        Yii::$app->session->removeAll();
//        Yii::$app->session->destroy();
//        var_dump(Yii::$app->session);
//        var_dump("hi");
//        die;
//        return $this->redirect(['']);
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
