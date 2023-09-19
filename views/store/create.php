<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Store $model */

$this->title = 'Create Store';
<<<<<<< HEAD
$this->params['breadcrumbs']['Home'] ='/';
$this->params['breadcrumbs']['Store'] = '/store/index';
$this->params['breadcrumbs']['Create Store'] = '/store/create';

=======
$this->params['breadcrumbs'][] = ['label' => 'Stores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
>>>>>>> origin/Mariam
?>
<div class="store-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
