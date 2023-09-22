<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\OrderItems $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="order-items-form">
    <div class="card card-primary">
    <?php $form = ActiveForm::begin(); ?>
        <div class="card-body">
            <div class="form-group col-md-3">
                <?= $form->field($model, 'order_id')->textInput() ?>
            </div>
            <div class="form-group col-md-3">
                <?= $form->field($model, 'product_id')->textInput() ?>
            </div>
            <div class="form-group col-md-3">
                <?= $form->field($model, 'quantity')->textInput() ?>
            </div>
            <div class="form-group col-md-3">
                <?= $form->field($model, 'price')->textInput() ?>
            </div>
            <div class="form-group col-md-3">
                <?= $form->field($model, 'revenue')->textInput() ?>
            </div>
            <div class="form-group col-md-3">
                <?= $form->field($model, 'cost')->textInput() ?>
            </div>
        </div>
        <div class="cart-footer">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>
    </div>
</div>
