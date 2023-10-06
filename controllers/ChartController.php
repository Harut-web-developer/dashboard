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

    public function actionGetData()
    {
        $post = $this->request->post();
        $store = intval($post['store']);
        $category = intval($post['category']);
        $start = $post['start'];
        $end = $post['end'];
        $condStore = [];
        $condCategory = [];
        if($store){
            $condStore = ['orders.store_id'=>$store];
        }
        if($category){
            $condCategory = ['category.id'=>$category];
        }
            $maxPrice = OrderItems::find()->select('MAX(round(order_items.price/order_items.quantity)) as maxPrice,
                     MAX(orders.total_price) as price,product.name,product.img')
                ->leftJoin('product', 'product.id = order_items.product_id')
                ->leftJoin('orders', 'orders.id = order_items.order_id')
                ->leftJoin('category', 'category.id = product.category_id')
                ->where(['BETWEEN', 'DATE(orders.date)', $start, $end])
                ->andWhere($condCategory)
                ->andWhere($condStore)
                ->asArray()
                ->one();
            $maxCount = OrderItems::find()->select('MAX(order_items.quantity) as maxCount,
                     product.name,product.img,SUM(order_items.revenue) as revenue, target.target_price')
                ->leftJoin('product', 'product.id = order_items.product_id')
                ->leftJoin('orders', 'orders.id = order_items.order_id')
                ->leftJoin('target', 'orders.store_id = target.store_id')
                ->leftJoin('category', 'category.id = product.category_id')
                ->where(['BETWEEN', 'DATE(orders.date)', $start, $end])
                ->andWhere($condCategory)
                ->andWhere($condStore)
                ->asArray()
                ->one();
            $ordersTotalPrice = Orders::find()->select('SUM(orders.total_price) as total')
                ->leftJoin('order_items', 'orders.id = order_items.order_id')
                ->leftJoin('product', 'product.id = order_items.product_id')
                ->leftJoin('category', 'category.id = product.category_id')
                ->where(['BETWEEN', 'DATE(orders.date)', $start, $end])
                ->andWhere($condCategory)
                ->andWhere($condStore)
                ->asArray()
                ->one();
            $ordersCount = Orders::find()
                ->leftJoin('order_items', 'orders.id = order_items.order_id')
                ->leftJoin('product', 'product.id = order_items.product_id')
                ->leftJoin('category', 'category.id = product.category_id')
                ->where(['BETWEEN', 'DATE(date)', $start, $end])
                ->andWhere($condCategory)
                ->andWhere($condStore)
                ->count();
            $averagePrice = 0;
            if($ordersCount) {
                $averagePrice = intval($ordersTotalPrice['total'] / $ordersCount);
            }
            $overageOrdersProcent = round(($averagePrice / $maxPrice['price']) * 100);
            $total_data = OrderItems::find()->select('target.target_price,DATE(orders.date) as date_only,
                    SUM(order_items.price) as price,SUM(order_items.revenue) as revenue')
                ->leftJoin('orders', 'orders.id = order_items.order_id')
                ->leftJoin('product', 'product.id = order_items.product_id')
                ->leftJoin('target', 'target.date = DATE(orders.date)')
                ->leftJoin('category', 'category.id = product.category_id')
                ->where(['BETWEEN', 'orders.date', $start, $end])
                ->andWhere($condStore)
                ->andWhere($condCategory)
                ->groupBy('target.date')
                ->asArray()
                ->all();
        $total_data_Month = OrderItems::find()->select('target.target_price,DATE(orders.date) as date_only,
                    SUM(order_items.price) as price,SUM(order_items.revenue) as revenue')
            ->leftJoin('orders', 'orders.id = order_items.order_id')
            ->leftJoin('product', 'product.id = order_items.product_id')
            ->leftJoin('target', 'target.date = DATE(orders.date)')
            ->leftJoin('category', 'category.id = product.category_id')
            ->where(['BETWEEN', 'orders.date', $start, $end])
            ->andWhere($condStore)
            ->andWhere($condCategory)
            ->groupBy('MONTH(target.date)')
            ->asArray()
            ->all();
        $days = [];
        $months = [];
        $label = [];
        $revenue = [];
        $price = [];
        $target_price = [];
        $firstDay = intval(date("j",strtotime($start)));
        $lastDay = intval(date("j",strtotime($end)));
        $firstMonth = intval(date('n',strtotime($start)));
        $endMonth = intval(date('n',strtotime($end)));
        if ($endMonth - $firstMonth == 0){
            if ($lastDay - $firstDay < 31) {
                for ($i = $firstDay; $i <= $lastDay; $i++) {
                    if ($i < 10) {
                        $dayData = date('Y-m-' . '0' . $i);
                        array_push($days, $dayData);
                    } else {
                        $dayData = date('Y-m-' . $i);
                        array_push($days, $dayData);
                    }
                }
                foreach ($days as $day) {
                    $found = false;
                    foreach ($total_data as $row) {
                        if ($row["date_only"] == $day) {
                            $label[] = $day;
                            $revenue[] = $row["revenue"];
                            $price[] = $row["price"];
                            $target_price[] = $row["target_price"];
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        $label[] = $day;
                        $revenue[] = 0;
                        $price[] = 0;
                        $target_price[] = 0;
                    }
                }
            }
        }else{
            for($n = 1;$n <= 12; $n++){
                if ($n < 10) {
                    $monthData = 0 . $n;
                    array_push($months, $monthData);
                } else {
                    $monthData = '' . $n;
                    array_push($months, $monthData);
                }
            }
            foreach ($months as $month){
                $index = false;
                foreach ($total_data_Month as $rowData){
                    if (substr($rowData['date_only'],5,2) == $month){
                        $label[] = date('Y-' . $month);
                        $revenue[] = $rowData["revenue"];
                        $price[] = $rowData["price"];
                        $target_price[] = $rowData["target_price"];
                        $index = true;
                        break;
                    }
                }
                if (!$index) {
                    $label[] = date('Y-' . $month);
                    $revenue[] = 0;
                    $price[] = 0;
                    $target_price[] = 0;
                }
            }

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
    }
}



