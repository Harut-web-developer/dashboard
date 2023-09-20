<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Target $model */

$this->title = $model->id;
$this->params['breadcrumbs']['Home'] ='/';
$this->params['breadcrumbs']['Targets'] = '/target/index';
$this->params['breadcrumbs']['View'] = '/target/view';
\yii\web\YiiAsset::register($this);
?>
<div class="target-view">

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
            'store_id',
            'date',
        ],
    ]) ?>

</div>
