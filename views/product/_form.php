<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Product $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="product-form">
    <div class="card card-primary">
        <?php $form = ActiveForm::begin(); ?>
        <div class="card-body prodForm">
            <div class="form-group col-md-3">
                <?= $form->field($model, 'category_id')->dropDownList($category) ?>
            </div>
            <div class="form-group col-md-3">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="form-group col-md-3">
                <?= $form->field($model, 'price')->textInput() ?>
            </div>
            <div class="form-group col-md-3">
                <?= $form->field($model, 'cost')->textInput() ?>
            </div>
            <div class="form-group col-md-10">
                <div class="form-group col-md-10">
                    <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>
                </div>
                <div class="form-group col-md-3">
                    <?= $form->field($model, 'img')->fileInput() ?>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
