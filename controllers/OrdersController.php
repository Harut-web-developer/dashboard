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
use yii\data\Pagination;
use yii\widgets\LinkPager;

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
        order_items.cost, order_items.name')
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
//                var_dump($model);
                $model->save(false);
                $payment->order_id = $model->id;
                $payment->type = $post['selPay'];
                $payment->price_of_pay = $post['inputPay'];
                $payment->save(false);
            for ($i = 0; $i < count($post['productid']); $i++) {
                $order_items = new OrderItems();
                $productName = Product::find()->select('name')->where(['=','id',$post['productid'][$i]])->one();
                $order_items->order_id = $model->id;
                $order_items->product_id = $post['productid'][$i];
                $order_items->quantity = $post['count_'][$i];
                $order_items->price = $post['price'][$i] * $post['count_'][$i];
                $order_items->cost = $post['cost'][$i] * $post['count_'][$i];
                $order_items->revenue = ($post['price'][$i] - $post['cost'][$i]) * $post['count_'][$i];
                $order_items->name = $productName->name;
                $order_items->save(false);
//                var_dump($order_items);
            }
//            exit;
                return $this->redirect(['index', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }
        $session = Yii::$app->session;
        $store = Store::find()->select('id,name')->asArray()->all();
        $store = ArrayHelper::map($store,'id','name');
        $manager = Users::find()->select('id,name')->where(['=','idrole', 2])->asArray()->all();
        $manager = ArrayHelper::map($manager,'id','name');
        $managerUnique = Users::find()->select('id,name')->where(['=','id', $session['user_id']])->asArray()->all();
        $managerUnique = ArrayHelper::map($managerUnique,'id','name');
//        $product = Product::find();
        $query = Product::find();
        $countQuery = clone $query;
        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $countQuery->count(),
        ]);
        $product = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        return $this->render('create', [
            'model' => $model,
            'store' => $store,
            'product' => $product,
            'manager' => $manager,
            'managerUnique' => $managerUnique,
            'pagination' => $pagination,
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
//        echo "<pre>";
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
            $items = $post['itemid'];
            $qty = 0;
            $tot_price = 0;
            foreach ($items as $k => $item) {
                if($item != 'null'){
                    $order_item = OrderItems::findOne($item);
                } else {
                    $order_item = new OrderItems();
                }
                $order_item->product_id = $post['productid'][$k];
                $order_item->quantity = $post['count_'][$k];
                $order_item->price = $post['price'][$k] * $post['count_'][$k];
                $qty += $order_item->quantity;
                $tot_price += $order_item->price;
                $order_item->order_id = $id;
                $order_item->cost = $post['cost'][$k] * $post['count_'][$k];
                $order_item->revenue = ($post['price'][$k] - $post['cost'][$k]) * $post['count_'][$k];
                $productName = Product::find()->select('name')->where(['=','id',$post['productid'][$k]])->one();
                $order_item->name = $productName->name;
                $order_item->save(false);
            }
            $order = Orders::findOne($id);
            $order->total_price = $tot_price;
            $order->quantity = $qty;
            $order->save(false);
//            var_dump($items);
//            exit;
            return $this->redirect(['index', 'id' => $model->id]);
        }
        $session = Yii::$app->session;
        $store = Store::find()->select('id,name')->asArray()->all();
        $store = ArrayHelper::map($store,'id','name');
        $query = Product::find();
        $countQuery = clone $query;
        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $countQuery->count(),
        ]);
        $product = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        $payment = Payment::find()->select('id,type,price_of_pay')->where(['=','order_id',$id])->asArray()->one();
        $order_items = OrderItems::find()->select('order_items.id,order_items.product_id,order_items.order_id,order_items.quantity,
        ROUND(order_items.price / order_items.quantity) as price,order_items.revenue,ROUND(order_items.cost / order_items.quantity) as cost,order_items.name')
            ->where(['=','order_items.order_id',$id])
            ->asArray()->all();
//        $order_items_id = OrderItems::find()->where(['=','order_items.order_id',$id])->exists();
//        var_dump($order_items_id);
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
            'managerUnique' => $managerUnique,
            'pagination' => $pagination,
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
        $order_item = OrderItems::find()->where(['order_id' => $id])->all();
            foreach ($order_item as $item){
                $item->delete();
            }
        return $this->redirect(['index']);
    }

    public function actionSearch(){
        $post = $this->request->post();
        if (isset($post)){

            $query = Product::find();
            if(isset($post['product'])){
                $query->where(['like', 'name', $post['product']]);
            }
            if(isset($post['isset_items']) && $post['isset_items'] !== ''){
                $items = explode(',',$post['isset_items']);
                $query->andWhere(['not',['id'=> $items]]);
            }
            $countQuery = clone $query;
            $totalcount = $countQuery->count();
            if ($totalcount <= 10){
                $page = '';
            }else{
                $pagination = new Pagination([
                    'defaultPageSize' => 10,
                    'totalCount' => $totalcount,
                ]);
                $page = LinkPager::widget(['pagination' => $pagination]);
                $query->offset($pagination->offset)
                    ->limit($pagination->limit);
            }
            $product = $query
                ->asArray()
                ->all();
            $res = [];
            $res['product'] = $product;
            $res['pagination'] = $page;
            return json_encode($res);
        }

    }

    public function actionDeleteTr(){
        $post = $this->request->post();
        if(isset($post)){
            $items = OrderItems::findOne($post['itemId']);
            $orders = Orders::findOne($items->order_id);
            $orders->quantity = $orders->quantity - $items->quantity;
            $orders->total_price = $orders->total_price - $items->price;
            $orders->save();
//            var_dump($orders);
            $itemDelete = OrderItems::findOne($post['itemId'])->delete();
            return json_encode($itemDelete);
        }
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
