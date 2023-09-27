<?php

namespace app\controllers;

use app\models\OrderItems;
use app\models\Orders;
use yii\web\Controller;


class ChartController extends  Controller{
    public function actionIndex(){
//        echo "<pre>";

        $maxPrice = OrderItems::find()->select('MAX(round(price/quantity)) as price')->asArray()->one();
        $maxCount = OrderItems::find()->select('MAX(quantity) as quantity')->asArray()->one();
        $ordersCount = Orders::find()->count();
        $ordersTotalPrice = Orders::find()->select('SUM(total_price) as total')->asArray()->one();
        $ordersMaxPrice = Orders::find()->select('MAX(total_price) as price')->asArray()->one();
        $averagePrice = intval($ordersTotalPrice['total'] / $ordersCount);
        $overageOrdersProcent = round(($averagePrice / $ordersMaxPrice['price']) * 100);

        return $this->render('index',[
            'maxPrice' => $maxPrice,
            'maxCount' => $maxCount,
            'ordersCount' => $ordersCount,
            'overageOrdersProcent' => $overageOrdersProcent,
        ]);
    }


}



