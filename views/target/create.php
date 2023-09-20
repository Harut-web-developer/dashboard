<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Target $model */

$this->title = 'Create Target';
$this->params['breadcrumbs']['Home'] ='/';
$this->params['breadcrumbs']['Target'] = '/target/index';
$this->params['breadcrumbs']['Create Target'] = '/target/create';
?>
<div class="target-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'store' => $store,
    ]) ?>

</div>
