<?php

namespace app\controllers;

use app\models\Category;
use app\models\Config;
use Yii;
use app\models\Product;
use app\models\ProductSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
    public $enableCsrfValidation = false;

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
        if ($action->id !== 'login' && Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
//        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    /**
     * Lists all Product models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
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
     * Displays a single Product model.
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

    public function actionGetProduct(){
//        $this->enableCsrfValidation = true;
        if (Yii::$app->request->post() || true) {
//            $post = Yii::$app->request->post();
            $post = $_GET;
//            console.log("category_id = " . $post['option']);

//            $config = Config::findOne([ 'category_id' => $post['option']])->procent;
            $category = Config::find()->where(['category_id' => $post['option']])->one();
            if ($category !== null) {
                $config = $category->procent;
                return json_encode(['category_id' => $config]);
            }
            else {
                $config = null;
                return json_encode(['category_id' => $config]);
            }
        }
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Product();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $imageName = $_FILES['Product']['name']['img'];
                $model->img = $imageName;
                $model->img = UploadedFile::getInstance($model, 'img');
                $model->img->saveAs('uploads/'.$imageName );
                if($model->save(false)){
                    return $this->redirect(['index', 'id' => $model->id]);
                }
                else
                {
                    return  false;
                }
            }
        }
        else
        {
            $model->loadDefaultValues();
        }
        $category = Category::find()->select('id, name')->asArray()->all();
        $category = ArrayHelper::map($category,'id', 'name');
        return $this->render('create', [
            'model' => $model,
            'category' => $category,
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $imageName = $_FILES['Product']['name']['img'];
            $model->img = $imageName;
            $model->img = UploadedFile::getInstance($model, 'img');
            $model->img->saveAs('uploads/'.$imageName );
            $model->save(false);
            return $this->redirect(['index', 'id' => $model->id]);
        }
        $category = Category::find()->select('id, name')->asArray()->all();
        $category = ArrayHelper::map($category,'id', 'name');
        return $this->render('update', [
            'model' => $model,
            'category' => $category,
        ]);

    }

    /**
     * Deletes an existing Product model.
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
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionSearching(){
        if (Yii::$app->request->isAjax && Yii::$app->request->post('option')) {
            $option = Yii::$app->request->post('option');
            $query_product = Product::find()
                ->select('id , name, description, keyword')
                ->orWhere(['like', 'name', $option])
                ->orWhere(['like', 'description' , $option])
                ->orWhere(['like', 'keyword', $option])
                ->asArray()->all();
            $query_category = Yii::$app->db->createCommand('SELECT id , name FROM category WHERE name LIKE :option')
                ->bindValue(':option', '%' . $option . '%')
                ->queryAll();
            $res = [];
            $res['query_product'] = $query_product;
            $res['query_category'] = $query_category;
            return json_encode($res);
        }
    }

}
