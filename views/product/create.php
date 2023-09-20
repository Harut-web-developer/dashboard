<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Product $model */

$this->title = 'Create Product';
$this->params['breadcrumbs']['Home'] ='/';
$this->params['breadcrumbs']['Product'] = '/product/index';
$this->params['breadcrumbs']['Create Product'] = '/product/create';
?>
<div class="product-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'product' => $product,
    ]) ?>

</div>
