<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\OrderItems $model */

$this->title = 'Update Order Items: ' . $model->id;
$this->params['breadcrumbs']['Home'] ='/';
$this->params['breadcrumbs']['Order-items'] = '/order-items/index';
$this->params['breadcrumbs']['Update'] = '/order-item/update';
?>
<div class="order-items-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
