<?php

namespace app\controllers;

use app\models\Users;
use Yii;
use app\models\Category;
use app\models\Config;
use app\models\ConfigSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ConfigController implements the CRUD actions for Config model.
 */
class ConfigController extends Controller
{
//    public $enableCsrfValidation = false;
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function beforeAction($action)
    {
        $session = Yii::$app->session;
        if ($action->id !== 'login' && !(isset($session['user_id']) && $session['logged'])) {
            return $this->redirect(['site/login']);
        } else if($action->id == 'login' && !(isset($session['user_id']) && $session['logged'])){
            return $this->actionLogin();
        }
        if(!$session['remember']){
            $res = Users::checkUser($session['user_id']);
            if(!$res){
                $this->redirect('/site/logout');
            }
        }else{
            $result = Users::checkUserAuthKey($session['user_id']);
            if(!$result){
                $this->redirect('site/logout');
            }
        }
        if($session['adminRole'] == 2 || $session['adminRole'] == 3){
            $this->redirect(['site/errors']);
        }
        return parent::beforeAction($action);
    }

    /**
     * Lists all Config models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ConfigSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        if($_POST){
            return $this->renderAjax('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'data_size' => 'max',
            ]);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Config model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionGetConfig(){
        $post = $this->request->post();
        if(isset($post)){
            $configCat = Config::find()->where(['=','category_id', $post['configCat']])->asArray()->one();
            if($configCat !== null){
                return json_encode(['msg' => 'warning']);
            }else{
                return json_encode(['msg' => 'empty']);
            }
        }
    }

    /**
     * Creates a new Config model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Config();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }
        $cat = Category::find()->select('id,name')->asArray()->all();
        $cat = ArrayHelper::map($cat,'id','name');
        return $this->render('create', [
            'model' => $model,
            'cat' => $cat,
        ]);
    }

    /**
     * Updates an existing Config model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $cat = Category::find()->select('id,name')->asArray()->all();
        $cat = ArrayHelper::map($cat,'id','name');
        return $this->render('update', [
            'model' => $model,
            'cat' => $cat,
        ]);
    }

    /**
     * Deletes an existing Config model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Config model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Config the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Config::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}