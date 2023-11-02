<?php

namespace app\controllers;

use app\models\Users;
use Couchbase\User;
use Yii;
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

    public function beforeAction($action)
    {
        $session = Yii::$app->session;
        if ($action->id !== 'login' && !(isset($session['user_id']) && $session['logged'])) {
            return $this->redirect(['site/login']);
        } else if($action->id == 'login' && !(isset($session['user_id']) && $session['logged'])){
            return $this->actionLogin();
        }
        if(!isset($_COOKIE['username'])){
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
        return parent::beforeAction($action);
    }

    /**
     * Lists all Orders models.
     *
     * @return string
     */
    public function actionIndex()
    {
//    echo "<pre>";
        $searchModel = new OrdersSearch();

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
     * Displays a single Orders model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $orderItemsTable = OrderItems::find()->select('order_items.id,order_items.order_id,
        order_items.product_id,order_items.quantity,order_items.price,order_items.revenue,
        order_items.cost, product.name')
            ->leftJoin('product','product.id = order_items.product_id')
            ->leftJoin('orders','orders.id = order_items.order_id')
            ->where(['=','orders.id', $id])
            ->asArray()
            ->all();
        $storeName = Store::find()->select('store.id,store.name')
            ->leftJoin('orders','orders.store_id = store.id')
            ->where(['=','orders.id',$id])
            ->one();
//        echo "<pre>";
//        var_dump($storeName);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'orderItemsTable' => $orderItemsTable,
            'storeName' => $storeName,
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
                $model->manager_id = $post['Orders']['manager_id'];
                $model->quantity = array_sum($post['count_']);
                $model->total_price = $post['Orders']["total_price"];
                $model->save(false);
                $payment->order_id = $model->id;
                $payment->type = $post['selPay'];
                $payment->price_of_pay = $post['inputPay'];
                $payment->save(false);

            for ($i = 0; $i < count($post['productid']); $i++) {
                $order_items = new OrderItems();
                $order_items->order_id = $model->id;
                $order_items->product_id = $post['productid'][$i];
                $order_items->quantity = $post['count_'][$i];
                $order_items->price = $post['price'][$i] * $post['count_'][$i];
                $order_items->cost = $post['cost'][$i] * $post['count_'][$i];
                $order_items->revenue = ($post['price'][$i] - $post['cost'][$i]) * $post['count_'][$i];
                $order_items->save(false);
            }
                return $this->redirect(['index', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }
        $session = Yii::$app->session;
        $store = Store::find()->select('id,name')->asArray()->all();
        $store = ArrayHelper::map($store,'id','name');
        $product = Product::find()->asArray()->all();
        $manager = Users::find()->select('id,name')->where(['=','idrole', 2])->asArray()->all();
        $manager = ArrayHelper::map($manager,'id','name');
        $managerUnique = Users::find()->select('id,name')->where(['=','id', $session['user_id']])->asArray()->all();
        $managerUnique = ArrayHelper::map($managerUnique,'id','name');

        return $this->render('create', [
            'model' => $model,
            'store' => $store,
            'product' => $product,
            'manager' => $manager,
            'managerUnique' => $managerUnique
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
        if ($this->request->isPost ) {
            $post = $this->request->post();
            $model->store_id = $post['Orders']['store_id'];
            $model->manager_id = $post['Orders']['manager_id'];
            $model->quantity = array_sum($post['count_']);
            $model->total_price = $post['Orders']["total_price"];
            $model->update();
            $payment = Payment::find()->where(['=','order_id', $model->id])->one();
            $payment->type = $post['selPay'];
            $payment->price_of_pay = $post['inputPay'];
            $payment->update();
            $items = OrderItems::find()->where(['=','order_id', $model->id])->all();
            foreach ($items as $k => $item) {
                $items[$k]->product_id = $post['productid'][$k];
                $items[$k]->quantity = $post['count_'][$k];
                $items[$k]->price = $post['price'][$k] * $post['count_'][$k];
                $items[$k]->cost = $post['cost'][$k] * $post['count_'][$k];
                $items[$k]->revenue = ($post['price'][$k] - $post['cost'][$k]) * $post['count_'][$k];
                $items[$k]->update();
            }
            return $this->redirect(['index', 'id' => $model->id]);
        }
        $session = Yii::$app->session;
        $store = Store::find()->select('id,name')->asArray()->all();
        $store = ArrayHelper::map($store,'id','name');
        $product = Product::find()->asArray()->all();
        $payment = Payment::find()->select('id,type,price_of_pay')->where(['=','order_id',$id])->asArray()->one();
        $order_items = OrderItems::find()->select('order_items.product_id,order_items.order_id,order_items.quantity,
        product.price,order_items.revenue,product.cost,product.name')
            ->leftJoin('product', 'product.id = order_items.product_id')
            ->where(['=','order_items.order_id',$id])
            ->asArray()->all();
        $manager = Users::find()->select('id,name')->where(['=','idrole', 2])->asArray()->all();
        $manager = ArrayHelper::map($manager,'id','name');
        $managerUnique = Users::find()->select('id,name')->where(['=','id', $session['user_id']])->asArray()->all();
        $managerUnique = ArrayHelper::map($managerUnique,'id','name');
        return $this->render('update', [
            'model' => $model,
            'store' => $store,
            'product' => $product,
            'payment' => $payment,
            'manager' => $manager,
            'order_items' => $order_items,
            'managerUnique' => $managerUnique
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
