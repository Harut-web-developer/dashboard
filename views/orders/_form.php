<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Orders $model */
/** @var yii\widgets\ActiveForm $form */
//echo "<pre>";
//var_dump($product);
//die;
?>

<div class="orders-form">
    <div class="card card-primary">
    <?php $form = ActiveForm::begin(); ?>
        <div class="card-body">
            <div class="form-group col-md-3">
                <?= $form->field($model, 'store_id')->dropDownList($store) ?>
            </div>
            <div class="form-group col-md-3">
                <?= $form->field($model, 'quantity')->input('number',['value' => 11,'disabled' => 'true']) ?>
            </div>
            <div class="form-group col-md-3">
                <?= $form->field($model, 'total_price')->input('number',['disabled' => 'true']) ?>
            </div>
        </div>
        <div class="mod">
            <!-- Button trigger modal -->
            <a data-toggle="modal" href="#exampleModal" class="btn btn-primary">+ add</a>

<!--            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">-->
<!--                + add-->
<!--            </button>-->

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">check</th>
                                        <th scope="col">name</th>
                                        <th scope="col">count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($product as $prod){
                                    ?>
                                    <tr class="table_tr">
                                        <td><?=$prod['id']?></td>
                                        <td><input data-id="<?=$prod['id']?>" type="checkbox"></td>
                                        <td class="prodname"><?=$prod['name']?></td>
                                        <input class="prodprice" type="hidden" value="<?=$prod['price']?>">
                                        <input class="prodcost" type="hidden" value="<?=$prod['cost']?>">
                                        <td class="prodcount"><input type="number" class="form-control"></td>
                                    </tr>
                                <?php
                                }
                                ?>

                                </tbody>
                            </table>
                            <div>
                                <button type="button" class="btn btn-success create">Create</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <table class="table order_products">
            <thead class="table-dark">
            <tr class="t">
                <th scope="col">#</th>
                <th scope="col">Product name</th>
                <th scope="col">Count</th>
                <th scope="col">Price</th>
                <th scope="col">Total</th>
                <th scope="col">Cost</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

        <div class="card-footer">
            <?= Html::submitButton('save', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>
    </div>
</div>
