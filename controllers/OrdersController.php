<?php

namespace app\controllers;

use app\models\OrderItems;
use app\models\Orders;
use app\models\OrdersSearch;
use app\models\Payment;
use app\models\Product;
use app\models\Store;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends Controller
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

    /**
     * Lists all Orders models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Orders model.
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

    /**
     * Creates a new Orders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
        public function actionCreate()
    {
        $model = new Orders();
        $payment = new Payment();
        if ($this->request->isPost) {
            $post = $this->request->post();
            if($post['selPay'] !== "" && $post['inputPay'] !== "") {
                $model->store_id = $post['Orders']['store_id'];
                $model->quantity = array_sum($post['count_']);
                $model->total_price = $post['Orders']["total_price"];
                $model->save();
                $payment->order_id = $model->id;
                $payment->type = $post['selPay'];
                $payment->price_of_pay = $post['inputPay'];
                $payment->save();

            for ($i = 0; $i < count($post['productid']); $i++) {
                $order_items = new OrderItems();
                $order_items->order_id = $model->id;
                $order_items->product_id = $post['productid'][$i];
                $order_items->quantity = $post['count_'][$i];
                $order_items->price = $post['price'][$i] * $post['count_'][$i];
                $order_items->cost = $post['cost'][$i] * $post['count_'][$i];
                $order_items->revenue = ($post['price'][$i] - $post['cost'][$i]) * $post['count_'][$i];
                $order_items->save();
            }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }
        $store = Store::find()->select('id,name')->asArray()->all();
        $store = ArrayHelper::map($store,'id','name');
        $product = Product::find()->asArray()->all();
//        $paytype = Payment::find()->select('id,type')->asArray()->all();
//        var_dump($paytype);

        return $this->render('create', [
            'model' => $model,
            'store' => $store,
            'product' => $product,
            'payment' => $payment,
        ]);
    }

    /**
     * Updates an existing Orders model.
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
        $store = Store::find()->select('id,name')->asArray()->all();
        $store = ArrayHelper::map($store,'id','name');
        return $this->render('update', [
            'model' => $model,
            'store' => $store,
        ]);
    }

    /**
     * Deletes an existing Orders model.
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
     * Finds the Orders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Orders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Orders::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
