<?php

use app\models\Store;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\StoreSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Stores';
$this->params['breadcrumbs']['Home'] ='/';
$this->params['breadcrumbs']['Stores'] = '/store/index';
?>
<?php if(!isset($data_size)){ ?>
<div class="store-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Store', ['create'], ['class' => 'btn btn-block btn-outline-dark col-md-2 btn-sm']) ?>
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
            'name',
            [
                'header'=>'Actions',
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Store $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>
    <?php
    }
    else{ ?>
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
                'name',
                [
                    'header'=>'Actions',
                    'class' => ActionColumn::className(),
                    'urlCreator' => function ($action, Store $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id' => $model->id]);
                    }
                ],
            ],
        ]); ?>

    <?php } ?>

</div>