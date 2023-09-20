<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Config $model */

$this->title = 'Update products procent: ' . $model->id;
$this->params['breadcrumbs']['Home'] ='/';
$this->params['breadcrumbs']['Products procent'] = '/config/index';
$this->params['breadcrumbs']['update procent'] = '/config/update';
?>
<div class="config-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
