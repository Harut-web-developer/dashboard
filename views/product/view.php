<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Product $model */

$this->title = $model->name;
$this->params['breadcrumbs']['Home'] ='/';
$this->params['breadcrumbs']['Product'] = '/product/index';
$this->params['breadcrumbs']['View'] = '/product/view';
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'category_id',
            'name',
            'description:ntext',
//            'img',
            'price',
            'cost',
            [
                'attribute' => 'img',
                'format' => 'raw',
                'value' => function($model){
                    return Html::img(Yii::getAlias('/uploads/'). $model->img,[
                        'alt'=>'yii2 - картинка в gridview',
                        'style' => 'width:150px;'
                    ]);
                },
            ],
        ],
    ]) ?>

</div>
