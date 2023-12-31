<?php

use app\models\Target;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\TargetSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Targets';
$this->params['breadcrumbs']['Home'] ='/';
$this->params['breadcrumbs']['Targets'] = '/target/index';
?>
<?php if(!isset($data_size)){ ?>
<div class="target-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <title><?= Html::encode($this->title); ?></title>
    <p>
        <?= Html::a('Create Target', ['create'], ['class' => 'btn btn-block btn-outline-dark col-sm-5 col-md-5 col-lg-3 btn-sm']) ?>
    </p>

    <!--Download XLSX-->
    <button class="downloadXLSX" >Download XLSX</button>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php
        $dataProvider->pagination->pageSize = 10;
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
//            'id',
//            'store_id',
            [
                'attribute' => 'store_id',
                'value' => function($model){
                    if ($model->storName) {
                        return $model->storName->name;
                    } else {
                        return 'empty';
                    }
                }
            ],
            'target_price',
            'date',
            [
                'header'=>'Actions',
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Target $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>
    <?php
    }
    else{ ?>
    <?php $dataProvider->pagination = false; ?>
        <title><?= Html::encode($this->title); ?></title>
        <?= GridView::widget([
            'tableOptions' => [
                'class'=>'table table-striped table-bordered chatgbti_',
            ],
            'options' => [
                'class' => 'summary deletesummary'
            ],
            'dataProvider' => $dataProvider,
    //        'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
    //            'id',
    //            'store_id',
                [
                    'attribute' => 'store_id',
                    'value' => function($model){
                        return $model->storName->name;
                    }
                ],
                'target_price',
                'date',
                [
                    'header'=>'Actions',
                    'class' => ActionColumn::className(),
                    'urlCreator' => function ($action, Target $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id' => $model->id]);
                    }
                ],
            ],
        ]); ?>
    <?php } ?>
</div>