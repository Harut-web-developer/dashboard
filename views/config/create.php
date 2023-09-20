<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Config $model */

$this->title = 'Create products procent';
$this->params['breadcrumbs']['Home'] ='/';
$this->params['breadcrumbs']['Products procent'] = '/config/index';
$this->params['breadcrumbs']['Create procent'] = '/config/create';
?>
<div class="config-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'cat' => $cat,
    ]) ?>

</div>
