<?php

namespace app\controllers;

use Yii;
use app\models\Category;
use app\models\CategorySearch;
use PHPUnit\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\db\Query;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPExcel_Reader_Excel2007;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
{

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
//        if ($action->id !== 'login' && Yii::$app->user->isGuest) {
//            return $this->redirect(['site/login']);
//        }
////        $this->enableCsrfValidation = false;
//        return parent::beforeAction($action);
    }

    /**
     * Lists all Category models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
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

    public function actionDeleteSelected()
    {
        if ($this->request->isPost) {
            $ids = Yii::$app->request->post('ids');
            $startId = Yii::$app->request->post('startId');
            $endId = Yii::$app->request->post('endId');
            if ($startId == "" && $endId == "" && $ids !== NULL) {
                Yii::$app->db->createCommand()
                    ->delete('category', ['id' => $ids])
                    ->execute();
                return json_encode(['success' => true]);
            } elseif ($startId !== "" && $endId !== "" && $ids == NULL) {
                if($startId < $endId){
                    Yii::$app->db->createCommand()
                        ->delete('category', ['between', 'id', $startId, $endId])
                        ->execute();
                    return json_encode(['success' => true]);
                }elseif ($startId === $endId){
                    Yii::$app->db->createCommand()
                        ->delete('category', ['=', 'id', $startId])
                        ->execute();
                    return json_encode(['success' => true]);
                }else{
                    return json_encode(['error1' => true]);
                }
            }elseif($startId === "" || $endId === "" && $ids == NULL){
                return json_encode(['error3' => true]);
            }else {
                return json_encode(['error2' => true]);
            }
        }
    }


    public function actionUpload()
    {
        require_once 'C:\OSPanel\domains\dashboard\PHPExcel-1.8_php8.1\PHPExcel-1.8\Classes\PHPExcel.php';
        $xlsxFile = UploadedFile::getInstanceByName('xlsxFile');

        if ($xlsxFile) {
            $inputFileName = $xlsxFile->tempName;
            $reader = new PHPExcel_Reader_Excel2007();
            $spreadsheet = $reader->load($inputFileName);
            $worksheet = $spreadsheet->getActiveSheet();
            $data = [];

            foreach ($worksheet->toArray() as $row) {
                $data[] = [
                    'parent_id' => $row[0],
                    'name' => $row[1],
                ];
            }
            Yii::$app->db->createCommand()
                ->batchInsert('category', ['parent_id', 'name'], $data)
                ->execute();
        }
        return $this->redirect(['index']);
    }

    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Category();
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }
        $categories = Category::find()->all();
        $categoryOptions = $this->formatCategoriesForDropdown($categories);
        return $this->render('create', [
            'model' => $model,
            'categoryOptions' => $categoryOptions,
        ]);
    }

    protected function formatCategoriesForDropdown($categories, $parentId = null, $level = 0)
    {
        $options = [];
        foreach ($categories as $category) {
            if ($category->parent_id === $parentId) {
                $prefix = str_repeat('-', $level);
                $options[$category->id] = $prefix . ' ' . $category->name;

                // Recursively fetch and format subcategories
                $subcategories = $this->formatCategoriesForDropdown($categories, $category->id, $level + 1);
                $options = ArrayHelper::merge($options, $subcategories);
            }
        }

        return $options;
    }

    /**
     * Updates an existing Category model.
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
//        $cat = Category::find()->select('id,name')->asArray()->all();
//        $cat = ArrayHelper::map($cat,'id','name');
        $categories = Category::find()->all();
        $categoryOptions = $this->formatCategoriesForDropdown($categories);
        return $this->render('update', [
            'model' => $model,
            'categoryOptions' => $categoryOptions,
        ]);
    }

    /**
     * Deletes an existing Category model.
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
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
