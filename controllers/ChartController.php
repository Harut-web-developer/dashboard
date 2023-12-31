<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Users;
use app\models\Category;
use app\models\OrderItems;
use app\models\Orders;
use app\models\Store;
use app\models\Target;
use yii\web\Controller;


class ChartController extends  Controller{
    public $enableCsrfValidation = false;

    public function beforeAction($action)
    {
        $session = Yii::$app->session;
        if ($action->id !== 'login' && !(isset($session['user_id']) && $session['logged'])) {
            return $this->redirect(['site/login']);
        } else if($action->id == 'login' && !(isset($session['user_id']) && $session['logged'])){
            return $this->actionLogin();
        }
//        $usersAuthKey = Users::findOne($session['user_id']);
//        if ($usersAuthKey->auth_key !== $session['auth_key']){
//            $this->redirect('/site/logout');
//        }
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
        $session = Yii::$app->session;
        $post = $this->request->post();
//        var_dump($post);
        $store = intval($post['store']);
        $category = intval($post['category']);
        $start = $post['start'];
        $end = $post['end'];
        $endDate = intval(substr($end, 8, 2)) + 1;
        $yearMonth = substr($end, 0, 8);
        $newDay = str_pad($endDate, 2, '0', STR_PAD_LEFT);
        $newEnd = $yearMonth . $newDay;
        $pay =$post['pay'];
        $condPay = [];
        $condStore = [];
        $condCategory = [];
        $response = [];
        $condRole = [];
        if ($session['adminRole'] == 2){
            $condRole = ['manager_id'=>$session['user_id']];
        }
        if($pay){
            $condPay = ['payment.type'=>$pay];
        }
        if($store){
            $condStore = ['orders.store_id'=>$store];
        }
        if($category){
            $condCategory = ['category.id'=>$category];
        }
        $orderTime = Orders::find()->where(['BETWEEN', 'DATE(date)', $start, $newEnd])->exists();
        $categoryExist = OrderItems::find()
            ->leftJoin('orders', 'orders.id = order_items.order_id')
            ->leftJoin('product', 'product.id = order_items.product_id')
            ->leftJoin('category', 'category.id = product.category_id')
            ->where(['BETWEEN', 'DATE(orders.date)', $start, $newEnd])
            ->andWhere($condCategory)
            ->exists();
        $storeExists = Store::find()
            ->leftJoin('orders', 'orders.store_id = store.id')
            ->leftJoin('order_items', 'orders.id = order_items.order_id')
            ->leftJoin('product', 'product.id = order_items.product_id')
            ->leftJoin('category', 'category.id = product.category_id')
            ->where(['BETWEEN', 'DATE(orders.date)', $start, $newEnd])
            ->andWhere($condStore)
            ->exists();
//        var_dump($storeExists);MAX(orders.total_price) as price, product.name,product.img
            if($start < $end || $start === $end){
                    if ($orderTime){
                        if ($categoryExist || $storeExists){
                            $maxPrice = OrderItems::find()->select('MAX(round(order_items.price/order_items.quantity)) as maxPrice,MAX(orders.total_price) as price, product.name,product.img')
                        ->leftJoin('product', 'product.id = order_items.product_id')
                        ->leftJoin('orders', 'orders.id = order_items.order_id')
                        ->leftJoin('category', 'category.id = product.category_id')
                        ->leftJoin('payment','payment.order_id = orders.id')
                        ->where(['BETWEEN', 'DATE(orders.date)', $start, $newEnd])
                        ->andWhere($condStore)
                        ->andWhere($condPay)
                        ->andWhere($condCategory)
                        ->andWhere($condRole)
                        ->asArray()
                        ->one();
//                    $maxPriceOrders = Orders::find()->select('MAX(orders.total_price) as price, product.name,product.img')
//                        ->leftJoin('order_items', 'orders.id = order_items.order_id')
//                        ->leftJoin('product', 'product.id = order_items.product_id')
//                        ->where(['BETWEEN', 'DATE(orders.date)', $start, $newEnd])
////                        ->andWhere(['=','product.price',intval($maxPrice['maxPrice'])])
////                        ->andWhere($condStore)
////                        ->andWhere($condPay)
////                        ->andWhere($condCategory)
//                        ->asArray()
//                        ->one();
//                            echo "<pre>";
//                            var_dump($maxPrice);
                    $maxCount = OrderItems::find()->select('MAX(order_items.quantity) as maxCount,
                     product.name,product.img,SUM(order_items.revenue) as revenue, target.target_price')
                        ->leftJoin('product', 'product.id = order_items.product_id')
                        ->leftJoin('orders', 'orders.id = order_items.order_id')
                        ->leftJoin('target', 'DATE(orders.date) = target.date')
                        ->leftJoin('category', 'category.id = product.category_id')
                        ->leftJoin('payment','payment.order_id = orders.id')
                        ->where(['BETWEEN', 'DATE(orders.date)', $start, $newEnd])
                        ->andWhere($condCategory)
                        ->andWhere($condStore)
                        ->andWhere($condPay)
                        ->andWhere($condRole)
                        ->asArray()
                        ->one();
//                    var_dump($maxCount);
//                    exit;
                    $ordersTotalPrice = OrderItems::find()->select('SUM(order_items.price) as total')
                        ->leftJoin('orders', 'orders.id = order_items.order_id')
                        ->leftJoin('product', 'product.id = order_items.product_id')
                        ->leftJoin('category', 'category.id = product.category_id')
                        ->leftJoin('payment','payment.order_id = orders.id')
                        ->where(['BETWEEN', 'DATE(orders.date)', $start, $newEnd])
                        ->andWhere($condCategory)
                        ->andWhere($condStore)
                        ->andWhere($condPay)
                        ->andWhere($condRole)
                        ->asArray()
                        ->one();
                    $ordersCount = Orders::find()
                        ->leftJoin('order_items', 'orders.id = order_items.order_id')
                        ->leftJoin('product', 'product.id = order_items.product_id')
                        ->leftJoin('category', 'category.id = product.category_id')
                        ->leftJoin('payment','payment.order_id = orders.id')
                        ->where(['BETWEEN', 'DATE(date)', $start, $newEnd])
                        ->andWhere($condCategory)
                        ->andWhere($condStore)
                        ->andWhere($condPay)
                        ->andWhere($condRole)
                        ->groupBy('order_items.order_id')
                        ->count();
                      $overageOrdersProcent = 0;
                    if($ordersCount) {
                        $averagePrice = intval($ordersTotalPrice['total'] / $ordersCount);
                        $overageOrdersProcent = round(($averagePrice / $maxPrice['price']) * 100);
                    }
                    $total_data = OrderItems::find()->select('target.target_price,DATE(orders.date) as date_only,
                    SUM(order_items.price) as price,SUM(order_items.revenue) as revenue, payment.price_of_pay')
                        ->leftJoin('orders', 'orders.id = order_items.order_id')
                        ->leftJoin('payment','orders.id = payment.order_id')
                        ->leftJoin('product', 'product.id = order_items.product_id')
                        ->leftJoin('target', 'target.date = DATE(orders.date)')
                        ->leftJoin('category', 'category.id = product.category_id')
                        ->where(['BETWEEN', 'orders.date', $start, $newEnd])
                        ->andWhere($condStore)
                        ->andWhere($condCategory)
                        ->andWhere($condPay)
                        ->andWhere($condRole)
                        ->groupBy('target.date')
                        ->asArray()
                        ->all();
                    $total_data_Month = OrderItems::find()->select('target.target_price,DATE(orders.date) as date_only,
                    SUM(order_items.price) as price,SUM(order_items.revenue) as revenue,MAX(payment.price_of_pay) as price_of_pay')
                        ->leftJoin('orders', 'orders.id = order_items.order_id')
                        ->leftJoin('payment','orders.id = payment.order_id')
                        ->leftJoin('product', 'product.id = order_items.product_id')
                        ->leftJoin('target', 'target.date = DATE(orders.date)')
                        ->leftJoin('category', 'category.id = product.category_id')
                        ->where(['BETWEEN', 'orders.date', $start, $newEnd])
                        ->andWhere($condStore)
                        ->andWhere($condCategory)
                        ->andWhere($condPay)
                        ->andWhere($condRole)
                        ->groupBy('MONTH(target.date)')
                        ->asArray()
                        ->all();
                    $days = [];
                    $months = [];
                    $label = [];
                    $revenue = [];
                    $price = [];
                    $paymentPrice = [];
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
                                        $paymentPrice[] = $row["price_of_pay"];
                                        $found = true;
                                        break;
                                    }
                                }
                                if (!$found) {
                                    $label[] = $day;
                                    $revenue[] = 0;
                                    $price[] = 0;
                                    $paymentPrice[] = 0;
                                    $target_price[] = 0;
                                }
                            }
                        }
                    }else{
                        $stMonth = Orders::find()->select('MIN(date) as date')->where(['BETWEEN', 'DATE(orders.date)', $start, $newEnd])
                            ->asArray()->all();
                        $lastMonth = Orders::find()->select('MAX(date) as date')
                            ->where(['BETWEEN', 'DATE(orders.date)', $start, $newEnd])
                            ->asArray()->all();
                        $stMonth = date('n',strtotime($stMonth[0]['date']));
                        $lastMonth = date('n',strtotime($lastMonth[0]['date']));
                        for($n = $stMonth;$n <= $lastMonth; $n++){
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
                                    $paymentPrice[] = $rowData["price_of_pay"];
                                    $target_price[] = $rowData["target_price"];
                                    $index = true;
                                    break;
                                }
                            }
                            if (!$index) {
                                $label[] = date('Y-' . $month);
                                $revenue[] = 0;
                                $price[] = 0;
                                $paymentPrice[] = 0;
                                $target_price[] = 0;
                            }
                        }
                    }
                    $response['maxPrice'] = $maxPrice;
                    $response['maxCount'] = $maxCount;
                    $response['overageProcent'] = $overageOrdersProcent;
                    $response['ordersCount'] = $ordersCount;
                    $response['ordersTotalPrice'] = $ordersTotalPrice;
                    $response['label'] = $label;
                    $response['revenue'] = $revenue;
                    $response['price'] = $price;
                    $response['paymentPrice'] = $paymentPrice;
                    $response['target_price'] = $target_price;
                    return json_encode($response);
                        }
//                        else{
//                            return json_encode(['msg' => 'error']);
//                        }
                }else{
                        $maxPrice = OrderItems::find()->select('MAX(round(order_items.price/order_items.quantity)) as maxPrice,MAX(orders.total_price) as price, product.name,product.img')
                            ->leftJoin('product', 'product.id = order_items.product_id')
                            ->leftJoin('orders', 'orders.id = order_items.order_id')
                            ->leftJoin('category', 'category.id = product.category_id')
                            ->leftJoin('payment','payment.order_id = orders.id')
                            ->where(['BETWEEN', 'DATE(orders.date)', $start, $newEnd])
                            ->andWhere($condStore)
                            ->andWhere($condPay)
                            ->andWhere($condCategory)
                            ->andWhere($condRole)
                            ->asArray()
                            ->one();
                        $maxCount = OrderItems::find()->select('MAX(order_items.quantity) as maxCount,
                     product.name,product.img,SUM(order_items.revenue) as revenue, target.target_price')
                            ->leftJoin('product', 'product.id = order_items.product_id')
                            ->leftJoin('orders', 'orders.id = order_items.order_id')
                            ->leftJoin('target', 'DATE(orders.date) = target.date')
                            ->leftJoin('category', 'category.id = product.category_id')
                            ->leftJoin('payment','payment.order_id = orders.id')
                            ->where(['BETWEEN', 'DATE(orders.date)', $start, $newEnd])
                            ->andWhere($condCategory)
                            ->andWhere($condStore)
                            ->andWhere($condPay)
                            ->andWhere($condRole)
                            ->asArray()
                            ->one();
//                    var_dump($maxCount);
//                    exit;
                        $ordersTotalPrice = OrderItems::find()->select('SUM(order_items.price) as total')
                            ->leftJoin('orders', 'orders.id = order_items.order_id')
                            ->leftJoin('product', 'product.id = order_items.product_id')
                            ->leftJoin('category', 'category.id = product.category_id')
                            ->leftJoin('payment','payment.order_id = orders.id')
                            ->where(['BETWEEN', 'DATE(orders.date)', $start, $newEnd])
                            ->andWhere($condCategory)
                            ->andWhere($condStore)
                            ->andWhere($condPay)
                            ->andWhere($condRole)
                            ->asArray()
                            ->one();
                        $ordersCount = Orders::find()
                            ->leftJoin('order_items', 'orders.id = order_items.order_id')
                            ->leftJoin('product', 'product.id = order_items.product_id')
                            ->leftJoin('category', 'category.id = product.category_id')
                            ->leftJoin('payment','payment.order_id = orders.id')
                            ->where(['BETWEEN', 'DATE(date)', $start, $newEnd])
                            ->andWhere($condCategory)
                            ->andWhere($condStore)
                            ->andWhere($condPay)
                            ->andWhere($condRole)
                            ->groupBy('order_items.order_id')
                            ->count();
                        $overageOrdersProcent = 0;
                        if($ordersCount) {
                            $averagePrice = intval($ordersTotalPrice['total'] / $ordersCount);
                            $overageOrdersProcent = round(($averagePrice / $maxPrice['price']) * 100);
                        }
                        $response['maxPrice'] = $maxPrice;
                        $response['maxCount'] = $maxCount;
                        $response['overageProcent'] = $overageOrdersProcent;
                        $response['ordersCount'] = $ordersCount;
                        $response['msg'] = 'danger';
                        return json_encode($response);
                }

            }else{
                return json_encode(['msg' => 'warning']);
            }

    }
}


