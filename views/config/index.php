<?php

use app\models\Config;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ConfigSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Products procent';
$this->params['breadcrumbs']['Home'] ='/';
$this->params['breadcrumbs']['Products procent'] = '/config/index';
?>
<?php if(!isset($data_size)){ ?>
<div class="config-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create products procent', ['create'], ['class' => 'btn btn-block btn-outline-dark col-md-2 btn-sm']) ?>
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
//            'category_id',
            [
                'attribute' => 'category_id',
                'value' => function($model){
                    return $model->categoryName->name;
                }
            ],
            'procent',
            [
                'header' => 'Actions',
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Config $model, $key, $index, $column) {
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
//            'category_id',
            [
                'attribute' => 'category_id',
                'value' => function($model){
                    return $model->categoryName->name;
                }
            ],
            'procent',
            [
                'header' => 'Actions',
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Config $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>
    <?php } ?>
</div>