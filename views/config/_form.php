<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Config $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="config-form">
    <div class="card card-primary">
        <?php $form = ActiveForm::begin(); ?>
        <div class="card-body">
            <div class="form-group col-md-3">
                <?= $form->field($model, 'category_id')->dropDownList($cat,['prompt' => '--choose category--','class' => 'configCat form-control']) ?>
            </div>
            <div class="form-group col-md-3">
                <?= $form->field($model, 'procent')->textInput(['class'=>'procentConfig form-control']) ?>
            </div>
        </div>
        <div class="card-footer">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary configCreate']) ?>
        </div>
    <?php ActiveForm::end(); ?>
    </div>
</div>
