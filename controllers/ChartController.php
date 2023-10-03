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
            'stores' => $stores,
            'categories' => $categories,
        ]);
    }

    public function actionGetData(){
        $post = $this->request->post();
        $store = $post['store'];
        $category = $post['category'];
        $start = $post['start'];
        $end = $post['end'];
        if($post['store'] == 0 && $post['category'] == 0){
            $maxPrice = OrderItems::find()->select('MAX(round(order_items.price/order_items.quantity)) as maxPrice,
             MAX(orders.total_price) as price,product.name,product.img')
                ->leftJoin('product','product.id = order_items.product_id')
                ->leftJoin('orders', 'orders.id = order_items.order_id')
                ->where(['BETWEEN', 'orders.date', $start, $end])
                ->asArray()
                ->one();
            $maxCount = OrderItems::find()->select('MAX(order_items.quantity) as maxCount,
             product.name,product.img,SUM(order_items.revenue) as revenue, target.target_price')
                ->leftJoin('product','product.id = order_items.product_id')
                ->leftJoin('orders', 'orders.id = order_items.order_id')
                ->leftJoin('target', 'orders.store_id = target.store_id')
                ->where(['BETWEEN', 'orders.date', $start, $end])
                ->asArray()
                ->one();
            $ordersTotalPrice = Orders::find()->select('SUM(total_price) as total')->asArray()->one();
            $ordersCount = Orders::find()->count();
            $averagePrice = intval($ordersTotalPrice['total'] / $ordersCount);
            $overageOrdersProcent = round(($averagePrice / $maxPrice['price']) * 100);
            $total_data = OrderItems::find()->select('target.target_price,DATE(orders.date) as date_only,
            SUM(order_items.price) as price,SUM(order_items.revenue) as revenue')
                ->leftJoin('orders','orders.id = order_items.order_id')
                ->leftJoin('target', 'target.date = DATE(orders.date)')
                ->where(['BETWEEN', 'orders.date', $start, $end])
                ->groupBy('target.date')
                ->asArray()
                ->all();
                $label = [];
                $revenue = [];
                $price = [];
                $target_price = [];
            for($i = 0; $i < count($total_data); $i++){
                array_push($label,$total_data[$i]['date_only']);
                array_push($revenue,intval($total_data[$i]['revenue']));
                array_push($price,intval($total_data[$i]['price']));
                array_push($target_price,intval($total_data[$i]['target_price']));
            }
            $response = [];
            $response['maxPrice'] = $maxPrice;
            $response['maxCount'] = $maxCount;
            $response['overageProcent'] = $overageOrdersProcent;
            $response['ordersCount'] = $ordersCount;
            $response['ordersTotalPrice'] = $ordersTotalPrice;
            $response['label'] = $label;
            $response['revenue'] = $revenue;
            $response['price'] = $price;
            $response['target_price'] = $target_price;
            return json_encode($response);
        }else if($post['category'] === 'null'){
                $maxPrice = OrderItems::find()->select('MAX(round(order_items.price/order_items.quantity)) as maxPrice,
                MAX(orders.total_price) as price, product.name,product.img')
                    ->leftJoin('orders', 'orders.id = order_items.order_id')
                    ->leftJoin('product','product.id = order_items.product_id')
                    ->where(['between', 'DATE(orders.date)', $start, $end])
                    ->andWhere(['=', 'orders.store_id', $store])
                    ->asArray()
                    ->one();
            $maxCount = OrderItems::find()->select('MAX(order_items.quantity) as maxCount,
            SUM(order_items.revenue) as revenue, product.name,product.img,target.target_price')
                ->leftJoin('orders', 'orders.id = order_items.order_id')
                ->leftJoin('product','product.id = order_items.product_id')
                ->leftJoin('target', 'orders.store_id = target.store_id')
                ->where(['between', 'DATE(orders.date)', $start, $end])
                ->andWhere(['=', 'orders.store_id', $store])
                ->andWhere('target.date = DATE(`orders`.`date`)')
                ->asArray()
                ->one();
            $ordersTotalPrice = Orders::find()->select('SUM(total_price) as total')
                ->where(['between', 'DATE(date)', $start, $end])
                ->andWhere( ['=', 'store_id',$store])
                ->asArray()
                ->one();
            $ordersCount = Orders::find()->where(['between', 'DATE(date)', $start, $end])
                ->andWhere( ['=', 'store_id',$store])
                ->count();
            $averagePrice = intval($ordersTotalPrice['total'] / $ordersCount);
            $overageOrdersProcent = round(($averagePrice / $maxPrice['price']) * 100);
            $total_data = OrderItems::find()->select('target.target_price,DATE(orders.date) as date_only,
            SUM(order_items.price) as price,SUM(order_items.revenue) as revenue')
                ->leftJoin('orders','orders.id = order_items.order_id')
                ->leftJoin('target', 'target.date = DATE(orders.date)')
                ->where(['between', 'DATE(orders.date)', $start, $end])
                ->andWhere( ['=', 'orders.store_id',$store])
                ->groupBy('target.date')
                ->asArray()
                ->all();
            $label = [];
            $revenue = [];
            $price = [];
            $target_price = [];
            for($i = 0; $i < count($total_data); $i++){
                array_push($label,$total_data[$i]['date_only']);
                array_push($revenue,intval($total_data[$i]['revenue']));
                array_push($price,intval($total_data[$i]['price']));
                array_push($target_price,intval($total_data[$i]['target_price']));
            }
            $response = [];
            $response['maxPrice'] = $maxPrice;
            $response['maxCount'] = $maxCount;
            $response['overageProcent'] = $overageOrdersProcent;
            $response['ordersCount'] = $ordersCount;
            $response['ordersTotalPrice'] = $ordersTotalPrice;
            $response['label'] = $label;
            $response['revenue'] = $revenue;
            $response['price'] = $price;
            $response['target_price'] = $target_price;
            return json_encode($response);
        }else if ($post['store'] === 'null'){
            $maxPrice = OrderItems::find()->select('MAX(round(order_items.price/order_items.quantity)) as maxPrice,
                MAX(orders.total_price) as price, product.name,product.img')
                ->leftJoin('orders', 'orders.id = order_items.order_id')
                ->leftJoin('product','product.id = order_items.product_id')
                ->leftJoin('category', 'category.id = product.category_id')
                ->where(['between', 'DATE(orders.date)', $start, $end])
                ->andWhere(['=', 'category.id', $category])
                ->asArray()
                ->one();
            $maxCount = OrderItems::find()->select('MAX(order_items.quantity) as maxCount,
            SUM(order_items.revenue) as revenue, product.name,product.img,target.target_price')
                ->leftJoin('orders', 'orders.id = order_items.order_id')
                ->leftJoin('product','product.id = order_items.product_id')
                ->leftJoin('category', 'category.id = product.category_id')
                ->leftJoin('target', 'orders.store_id = target.store_id')
                ->where(['between', 'DATE(orders.date)', $start, $end])
                ->andWhere(['=', 'category.id', $category])
                ->andWhere('target.date = DATE(`orders`.`date`)')
                ->asArray()
                ->one();
            $ordersTotalPrice = OrderItems::find()->select('SUM(order_items.price) as total')
                ->leftJoin('orders', 'orders.id = order_items.order_id')
                ->leftJoin('product','product.id = order_items.product_id')
                ->leftJoin('category', 'category.id = product.category_id')
                ->where(['between', 'DATE(orders.date)', $start, $end])
                ->andWhere( ['=', 'category.id',$category])
                ->asArray()
                ->one();
            $ordersCount = OrderItems::find()
                ->leftJoin('orders', 'orders.id = order_items.order_id')
                ->leftJoin('product','product.id = order_items.product_id')
                ->leftJoin('category', 'category.id = product.category_id')
                ->where(['between', 'DATE(orders.date)', $start, $end])
                ->andWhere( ['=', 'category.id',$category])
                ->count();
            $averagePrice = intval($ordersTotalPrice['total'] / $ordersCount);
            $overageOrdersProcent = round(($averagePrice / $maxPrice['price']) * 100);
            $total_data = OrderItems::find()->select('target.target_price,DATE(orders.date) as date_only,
            SUM(order_items.price) as price,SUM(order_items.revenue) as revenue')
                ->leftJoin('orders','orders.id = order_items.order_id')
                ->leftJoin('target', 'target.date = DATE(orders.date)')
                ->leftJoin('product','product.id = order_items.product_id')
                ->leftJoin('category', 'category.id = product.category_id')
                ->where(['between', 'DATE(orders.date)', $start, $end])
                ->andWhere( ['=', 'category.id',$category])
                ->groupBy('target.date')
                ->asArray()
                ->all();
            $label = [];
            $revenue = [];
            $price = [];
            $target_price = [];
            for($i = 0; $i < count($total_data); $i++){
                array_push($label,$total_data[$i]['date_only']);
                array_push($revenue,intval($total_data[$i]['revenue']));
                array_push($price,intval($total_data[$i]['price']));
                array_push($target_price,intval($total_data[$i]['target_price']));
            }
            $response = [];
            $response['maxPrice'] = $maxPrice;
            $response['maxCount'] = $maxCount;
            $response['overageProcent'] = $overageOrdersProcent;
            $response['ordersCount'] = $ordersCount;
            $response['ordersTotalPrice'] = $ordersTotalPrice;
            $response['label'] = $label;
            $response['revenue'] = $revenue;
            $response['price'] = $price;
            $response['target_price'] = $target_price;
            return json_encode($response);
            }else{
            $maxPrice = OrderItems::find()->select('round(order_items.price/order_items.quantity) as maxPrice,
            MAX(orders.total_price) as price, product.name,product.img')
                ->leftJoin('orders', 'orders.id = order_items.order_id')
                ->leftJoin('product','product.id = order_items.product_id')
                ->leftJoin('category', 'category.id = product.category_id')
                ->where(['between', 'DATE(orders.date)', $start, $end])
                ->andWhere(['=', 'category.id', $category])
                ->andWhere(['=', 'orders.store_id', $store])
                ->asArray()
                ->one();
            $maxCount = OrderItems::find()->select('MAX(order_items.quantity) as maxCount,
            SUM(order_items.revenue) as revenue, product.name,product.img,target.target_price')
                ->leftJoin('orders', 'orders.id = order_items.order_id')
                ->leftJoin('product','product.id = order_items.product_id')
                ->leftJoin('category', 'category.id = product.category_id')
                ->leftJoin('target', 'orders.store_id = target.store_id')
                ->where(['between', 'DATE(orders.date)', $start, $end])
                ->andWhere(['=', 'category.id', $category])
                ->andWhere(['=', 'orders.store_id', $store])
                ->andWhere('target.date = DATE(`orders`.`date`)')
                ->asArray()
                ->one();
            $ordersTotalPrice = OrderItems::find()->select('SUM(order_items.price) as total')
                ->leftJoin('orders', 'orders.id = order_items.order_id')
                ->leftJoin('product','product.id = order_items.product_id')
                ->leftJoin('category', 'category.id = product.category_id')
                ->where(['between', 'DATE(orders.date)', $start, $end])
                ->andWhere( ['=', 'category.id',$category])
                ->andWhere( ['=', 'orders.store_id',$store])
                ->asArray()
                ->one();
            $ordersCount = OrderItems::find()
                ->leftJoin('orders', 'orders.id = order_items.order_id')
                ->leftJoin('product','product.id = order_items.product_id')
                ->leftJoin('category', 'category.id = product.category_id')
                ->where(['between', 'DATE(orders.date)', $start, $end])
                ->andWhere( ['=', 'category.id',$category])
                ->andWhere( ['=', 'orders.store_id',$store])
                ->count();
            $averagePrice = intval($ordersTotalPrice['total'] / $ordersCount);
            $overageOrdersProcent = round(($averagePrice / $maxPrice['price']) * 100);
            $total_data = OrderItems::find()->select('target.target_price,DATE(orders.date) as date_only,
            SUM(order_items.price) as price,SUM(order_items.revenue) as revenue')
                ->leftJoin('orders','orders.id = order_items.order_id')
                ->leftJoin('target', 'target.date = DATE(orders.date)')
                ->leftJoin('product','product.id = order_items.product_id')
                ->leftJoin('category', 'category.id = product.category_id')
                ->where(['between', 'DATE(orders.date)', $start, $end])
                ->andWhere( ['=', 'category.id',$category])
                ->andWhere( ['=', 'orders.store_id',$store])
                ->groupBy('target.date')
                ->asArray()
                ->all();
            $label = [];
            $revenue = [];
            $price = [];
            $target_price = [];
            for($i = 0; $i < count($total_data); $i++){
                array_push($label,$total_data[$i]['date_only']);
                array_push($revenue,intval($total_data[$i]['revenue']));
                array_push($price,intval($total_data[$i]['price']));
                array_push($target_price,intval($total_data[$i]['target_price']));
            }
            $response = [];
            $response['maxPrice'] = $maxPrice;
            $response['maxCount'] = $maxCount;
            $response['overageProcent'] = $overageOrdersProcent;
            $response['ordersCount'] = $ordersCount;
            $response['ordersTotalPrice'] = $ordersTotalPrice;
            $response['label'] = $label;
            $response['revenue'] = $revenue;
            $response['price'] = $price;
            $response['target_price'] = $target_price;
            return json_encode($response);
//                var_dump($total_data);

        }


    }


}



