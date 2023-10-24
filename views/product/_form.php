<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Product $model */
/** @var yii\widgets\ActiveForm $form */
?>
<!--onchange="location.reload(true)"-->
<div class="product-form">
    <div class="card card-primary">
        <?php $form = ActiveForm::begin(); ?>
        <div class="card-body prodForm">
            <div class="form-group col-md-3">
                <?= $form->field($model, 'category_id')->dropDownList($category, ['class' => 'category form-control', 'prompt' => 'Main category']) ?>
            </div>
            <div class="form-group col-md-3">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="form-group col-md-3">
                <?= $form->field($model, 'price')->input('number',['class' => 'price form-control']) ?>
            </div>
            <div class="form-group col-md-3">
                <?= $form->field($model, 'cost')->input('number',['class' => 'cost form-control']) ?>
            </div>
            <div class="form-group col-md-10">
                <div class="form-group col-md-10">
                    <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>
                </div>
                <div class="form-group col-md-3">
                    <?= $form->field($model, 'keyword')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="form-group col-md-3">
                    <?= $form->field($model, 'img')->fileInput() ?>
                </div>
            </div>

        </div>
        <div class="card-footer">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary productCreate']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
