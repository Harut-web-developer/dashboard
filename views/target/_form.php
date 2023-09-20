<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Target $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="target-form">
    <div class="card card-primary">
        <?php $form = ActiveForm::begin(); ?>
        <div class="card-body">
            <div class="form-group col-md-3">
                <?= $form->field($model, 'store_id')->dropDownList($store) ?>
            </div>
            <div class="form-group col-md-3">
                <?= $form->field($model, 'date')->input('date') ?>
            </div>
        </div>
        <div class="card-footer">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
