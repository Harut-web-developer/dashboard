<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
?>

<?php
$session = Yii::$app->session;
if(isset($_COOKIE['username']) && isset($_COOKIE['password'])){
    $username = $_COOKIE['username'];
    $password = $_COOKIE['password'];
}else{
    $username = '';
    $password = '';
}
//$error = $session->get('error');
if ($session['error'] === NULL){
    $error = '';
}else{
    $error = $session['error'];
}
?>

<!--<div class="alert alert-danger alert-dismissible">-->
<!--    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>-->
    <strong><?=$error?></strong>
<!--</div>-->

<div class="col"><img class="img-profile rounded-circle d-flex justify-content-center" src="/img/undraw_profile.svg" style="width: 200px; margin: 60px 60px 60px 60px;"></div>

<?php $form = ActiveForm::begin(['id' => 'login-form',]); ?>
<div class="form-group">
    <?= $form->field($model, 'username')->textInput(['value' => $username]) ?>
</div>
<div class="form-group">
    <?= $form->field($model, 'password')->passwordInput(['value' => $password]) ?>
</div>
<div class="form-group">
    <?= $form->field($model, 'rememberMe')->checkbox([
        'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
    ]) ?>
</div>
<?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
<?php ActiveForm::end(); ?>
</div>

<?php
$session->remove('error');
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
