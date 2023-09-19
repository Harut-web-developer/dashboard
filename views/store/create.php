<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Store $model */

$this->title = 'Create Store';
$this->params['breadcrumbs']['Home'] ='/';
$this->params['breadcrumbs']['Store'] = '/store/index';
$this->params['breadcrumbs']['Create Store'] = '/store/create';


?>
<div class="store-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
