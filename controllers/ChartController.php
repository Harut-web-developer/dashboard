<?php

namespace app\controllers;

use app\models\Category;
use app\models\OrderItems;
use app\models\Orders;
use app\models\Store;
use app\models\Target;
use yii\web\Controller;


class ChartController extends  Controller{
    public $enableCsrfValidation = false;
    public function actionIndex(){
        $stores = Store::find()->select('id,name')->asArray()->all();
        $categories = Category::find()->select('id,name')->asArray()->all();

        return $this->render('index',[
//            'maxPrice' => $maxPrice,
//            'maxCount' => $maxCount,
//            'ordersCount' => $ordersCount,
//            'overageOrdersProcent' => $overageOrdersProcent,
            'stores' => $stores,
            'categories' => $categories,
        ]);
    }

    public function actionGetData(){
        $post = $this->request->post();
        $start = $post['start'];
        $end = $post['end'];
        if($post['store'] == 0){
            $maxPrice = OrderItems::find()->select('MAX(round(order_items.price/order_items.quantity)) as maxPrice,
             MAX(orders.total_price) as price,product.name,product.img')
                ->leftJoin('product','product.id = order_items.product_id')
                ->leftJoin('orders', 'orders.id = order_items.order_id')
                ->where(['between', 'orders.date', $start, $end])
                ->asArray()
                ->one();
            $maxCount = OrderItems::find()->select('MAX(order_items.quantity) as maxCount,
             product.name,product.img,SUM(order_items.revenue) as revenue, target.target_price')
                ->leftJoin('product','product.id = order_items.product_id')
                ->leftJoin('orders', 'orders.id = order_items.order_id')
                ->leftJoin('target', 'orders.store_id = target.store_id')
                ->where(['between', 'orders.date', $start, $end])
                ->asArray()
                ->one();
            $ordersTotalPrice = Orders::find()->select('SUM(total_price) as total')->asArray()->one();
            $ordersCount = Orders::find()->count();
            $averagePrice = intval($ordersTotalPrice['total'] / $ordersCount);
            $overageOrdersProcent = round(($averagePrice / $maxPrice['price']) * 100);
            $titles = Orders::find()->select('id, DATE(date) as date_only,SUM(total_price) as totalPrice')
                ->groupBy('date_only')
                ->asArray()
                ->all();
            foreach ($titles as $key => $title){
            var_dump($key);
            }
//            var_dump($titles);
            $response = [];
            $response['maxPrice'] = $maxPrice;
            $response['maxCount'] = $maxCount;
            $response['overageProcent'] = $overageOrdersProcent;
            $response['ordersCount'] = $ordersCount;
            $response['ordersTotalPrice'] = $ordersTotalPrice;
//            $response['title'] = $title;

            return json_encode($response);
        }


    }


}



