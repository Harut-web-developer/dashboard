<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\OrderItems $model */

$this->title = 'Create Order Items';
$this->params['breadcrumbs']['Home'] ='/';
$this->params['breadcrumbs']['Order-items'] = '/order-items/index';
$this->params['breadcrumbs']['Create'] = '/order-item/create';
?>
<div class="order-items-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
