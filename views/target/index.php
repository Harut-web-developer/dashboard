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
<div class="target-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Target', ['create'], ['class' => 'btn btn-block btn-outline-dark col-md-2 btn-sm']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'store_id',
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


</div>