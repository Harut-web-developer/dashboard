<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Orders $model */
/** @var yii\widgets\ActiveForm $form */
//echo "<pre>";
?>

<?php
//var_dump($model);
if (isset($model->id)){
    ?>
    <div class="orders-form">
        <div class="card card-primary">
            <?php $form = ActiveForm::begin(); ?>
            <div class="card-body">
                <div class="form-group col-md-2">
                    <?= $form->field($model, 'manager_id')->dropDownList($manager,
                        [
                            'options' => [$model->manager_id => ['selected' => true]]
                        ]) ?>
                </div>
                <div class="form-group col-md-2">
                    <?= $form->field($model, 'store_id')->dropDownList($store,
                        [
                            'options' => [$model->store_id => ['selected' => true]]
                        ]) ?>
                </div>
                <div class="form-group col-md-2">
                    <label for="selectPay">Type of pay</label>
                    <select id="selectPay" name="selPay" class="selectPay form-control col-md-12">
                        <option value="">choose type of pay</option>
                        <option value="cash" <?php echo ($payment['type'] === 'cash') ? 'selected' : ''; ?>>cash</option>
                        <option value="card" <?php echo ($payment['type'] === 'card') ? 'selected' : ''; ?>>by card</option>
                        <option value="credit" <?php echo ($payment['type'] === 'credit') ? 'selected' : ''; ?>>credit</option>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="inpPay">Price of pay</label>
                    <input type="number" id="inpPay" class="inpPay form-control col-md-12" name="inputPay" value="<?=$payment['price_of_pay']?>">
                </div>
                <div class="form-group col-md-2">
                    <?= $form->field($model, 'total_price')->input('number',['readonly' => true,'class' => 'last_total_price form-control']) ?>
                </div>
            </div>
            <div class="mod">
                <!-- Button trigger modal -->
                <a data-toggle="modal" href="#examMod" class="btn btn-primary">+ add</a>
                <!-- Modal -->
                <div class="modal fade" id="examMod" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                        <th scope="col">unit</th>
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
                                            <td class="produnit"><?=$prod['unit']?></td>
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
                <?php
                        foreach ($order_items as $order){
                            ?>
                            <tr class="product_tr">
                                <th scope="row"><?=$order['product_id']?><input type="hidden" name="productid[]" value="<?=$order['product_id']?>"></th>
                                <td class="name"><?=$order['name']?></td>
                                <td class="count"><input type="number" name="count_[]" value="<?=$order['quantity']?>" class="form-control countProduct"></td>
                                <td class="price"><?=$order['price']?><input type="hidden" name="price[]" value="<?=$order['price']?>"></td>
                                <input type="hidden" name="total[]" value="<?=$order['price'] *  $order['quantity']?>">
                                <td class="total"><?=$order['price'] *  $order['quantity']?></td>
                                <td class="cost"><?=$order['cost']?><input type="hidden" name="cost[]" value="<?=$order['cost']?>"></td>
                                <td class="btnn"><button type="button" class="btn btn-outline-danger delItems">Delete</button></td>
                            </tr>
                            <?php
                        }
                ?>
                </tbody>
            </table>

            <div class="card-footer">
                <?= Html::submitButton('save', ['class' => 'btn btn-primary ordersCreate']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <?php
}else{
    ?>
    <div class="orders-form">
        <div class="card card-primary">
            <?php $form = ActiveForm::begin(); ?>
            <div class="card-body">
                <div class="form-group col-md-2">
                    <?= $form->field($model, 'manager_id')->dropDownList($manager) ?>
                </div>
                <div class="form-group col-md-2">
                    <?= $form->field($model, 'store_id')->dropDownList($store) ?>
                </div>
                <div class="form-group col-md-2">
                    <label for="selectPay">Type of pay</label>
                    <select id="selectPay" name="selPay" class="selectPay form-control col-md-12" >
                        <option value="">choose type of pay</option>
                        <option value="cash">cash</option>
                        <option value="card">by card</option>
                        <option value="credit">credit</option>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="inpPay">Price of pay</label>
                    <input type="number" id="inpPay" class="inpPay form-control col-md-12" name="inputPay">
                </div>
                <div class="form-group col-md-2">
                    <?= $form->field($model, 'total_price')->input('number',['readonly' => true,'class' => 'last_total_price form-control']) ?>
                </div>
            </div>
            <div class="mod">
                <!-- Button trigger modal -->
                <a data-toggle="modal" href="#examMod" class="btn btn-primary">+ add</a>

                <!--            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">-->
                <!--                + add-->
                <!--            </button>-->

                <!-- Modal -->
                <div class="modal fade" id="examMod" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                        <th scope="col">unit</th>
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
                                            <td class="produnit"><?=$prod['unit']?></td>
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
                <?= Html::submitButton('save', ['class' => 'btn btn-primary ordersCreate']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
<?php
}
//die;
?>


<?php
$this->registerJsFile(
    '@web/js/order.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);
?>
