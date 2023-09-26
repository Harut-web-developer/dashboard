<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Product $model */

$this->title = 'Update Product: ' . $model->name;
$this->params['breadcrumbs']['Home'] ='/';
$this->params['breadcrumbs']['Product'] = '/product/index';
$this->params['breadcrumbs']['View'] = '/product/view';
?>
<div class="product-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'category' => $category,
    ]) ?>

</div>
