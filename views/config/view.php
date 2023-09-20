<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Config $model */

$this->title = $model->id;
$this->params['breadcrumbs']['Home'] ='/';
$this->params['breadcrumbs']['Products procent'] = '/config/index';
$this->params['breadcrumbs']['View procent'] = '/config/view';
\yii\web\YiiAsset::register($this);
?>
<div class="config-view">

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
            'procent',
        ],
    ]) ?>

</div>
