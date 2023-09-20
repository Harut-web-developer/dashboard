<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Store $model */

$this->title = 'Create Store';
$this->params['breadcrumbs']['Home'] ='/';
$this->params['breadcrumbs']['Store'] = '/store/index';
$this->params['breadcrumbs']['Create Store'] = '/store/create';
<<<<<<< HEAD

=======
>>>>>>> bdb8b5a684d4ee1b7fe3eb3087eef997fa625e2a

?>
<div class="store-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
