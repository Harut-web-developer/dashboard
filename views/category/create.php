<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Category $model */

$this->title = 'Create Category';
$this->params['breadcrumbs']['Home'] ='/';
$this->params['breadcrumbs']['Category'] = '/category/index';
$this->params['breadcrumbs']['Create'] = '/category/create';


?>
<div class="category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'categoryOptions' => $categoryOptions,
    ]) ?>

</div>
