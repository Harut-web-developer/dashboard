<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Store $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="store-form">
<<<<<<< HEAD
<<<<<<< HEAD
    <div class="card card-primary">
    <?php $form = ActiveForm::begin(); ?>
        <div class="card-body">
            <div class="form-group col-md-3">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="card-footer">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>
    </div>

=======

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

>>>>>>> origin/Mariam
=======
    <div class="card card-primary">
        <?php $form = ActiveForm::begin(); ?>
            <div class="card-body">
                <div class="form-group col-md-3">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="card-footer">
                <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
>>>>>>> bdb8b5a684d4ee1b7fe3eb3087eef997fa625e2a
</div>
