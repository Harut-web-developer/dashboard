<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Target $model */

$this->title = 'Update Target: ' . $model->id;
$this->params['breadcrumbs']['Home'] ='/';
$this->params['breadcrumbs']['Target'] = '/target/index';
$this->params['breadcrumbs']['View'] = '/target/view';
?>
<div class="target-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'store' => $store,
    ]) ?>

</div>
