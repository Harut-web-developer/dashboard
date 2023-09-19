<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Category $model */
/** @var yii\widgets\ActiveForm $form */

?>

<div class="category-form">
    <div class="card card-primary">
        <?php $form = ActiveForm::begin(); ?>
        <div class="card-body">
            <div class="form-group col-md-3">
                <?= $form->field($model, 'parent_id')->dropDownList($cat,['prompt' => 'Main category']) ?>
            </div>
            <div class="form-group col-md-3">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="card-footer">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
