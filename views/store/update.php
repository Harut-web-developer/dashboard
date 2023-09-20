<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Store $model */

$this->title = 'Update Store: ' . $model->name;
$this->params['breadcrumbs']['Home'] ='/';
$this->params['breadcrumbs']['Store'] = '/store/index';
$this->params['breadcrumbs']['Update'] = '/store/update';
?>
<div class="store-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
