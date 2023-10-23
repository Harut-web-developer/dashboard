<?php

use app\models\Orders;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\OrdersSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs']['Home'] ='/';
$this->params['breadcrumbs']['Orders'] = '/orders/index';
?>
<?php if(!isset($data_size)){ ?>
<div class="orders-index">
    <title><?= Html::encode($this->title); ?></title>
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Orders', ['create'], ['class' => 'btn btn-block btn-outline-dark col-md-2 btn-sm']) ?>
    </p>

    <!--Download XLSX-->
    <button class="downloadXLSX" >Download XLSX</button>
    <?php
    $dataProvider->pagination->pageSize = 10;
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
//            'id',
            'store_id',
            'quantity',
            'total_price',
            'date',
            [
                'header' => 'Actions',
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Orders $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>
    <?php
    }
    else{ ?>
        <title><?= Html::encode($this->title); ?></title>
        <?php $dataProvider->pagination = false; ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => [
                'class'=>'table table-striped table-bordered chatgbti_',
            ],
            'options' => [
                'class' => 'summary deletesummary'
            ],
//        'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
//            'id',
                'store_id',
                'quantity',
                'total_price',
                'date',
                [
                    'header' => 'Actions',
                    'class' => ActionColumn::className(),
                    'urlCreator' => function ($action, Orders $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id' => $model->id]);
                    }
                ],
            ],
        ]); ?>

    <?php } ?>

</div>

