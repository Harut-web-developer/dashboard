<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Orders $model */

$this->title = 'Update Orders: ' . $model->id;
$this->params['breadcrumbs']['Home'] ='/';
$this->params['breadcrumbs']['Orders'] = '/orders/index';
$this->params['breadcrumbs']['Update'] = '/orders/update';
?>
<div class="orders-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'store' => $store,
        'product' => $product,
        'payment' => $payment,
        'manager' => $manager,
        'order_items' => $order_items,
        'managerUnique' => $managerUnique
    ]) ?>

</div>
