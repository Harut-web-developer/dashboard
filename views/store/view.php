<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Store $model */

<<<<<<< HEAD

$this->title = 'Create Category';
$this->params['breadcrumbs']['Home'] ='/';
$this->params['breadcrumbs']['Category'] = '/category/index';
$this->params['breadcrumbs']['Views'] = '/category/view';

=======
$this->title = $model->name;
$this->params['breadcrumbs']['Home'] ='/';
$this->params['breadcrumbs']['Store'] = '/store/index';
$this->params['breadcrumbs']['View'] = '/store/view';
\yii\web\YiiAsset::register($this);
>>>>>>> bdb8b5a684d4ee1b7fe3eb3087eef997fa625e2a
?>
<div class="store-view">

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
            'name',
        ],
    ]) ?>

</div>
