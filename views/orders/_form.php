<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Orders $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="orders-form">
    <div class="card card-primary">
    <?php $form = ActiveForm::begin(); ?>
        <div class="card-body">
            <div class="form-group col-md-3">
                <?= $form->field($model, 'store_id')->dropDownList($store) ?>
            </div>
            <div class="form-group col-md-3">
                <?= $form->field($model, 'quantity')->input('number',['value' => 11]) ?>
            </div>
            <div class="form-group col-md-3">
                <?= $form->field($model, 'total_price')->input('number') ?>
            </div>
        </div>
        <div class="card-footer">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>
    </div>
</div>
