<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Orders $model */

$this->title = $model->id;
$this->params['breadcrumbs']['Home'] ='/';
$this->params['breadcrumbs']['Orders'] = '/orders/index';
$this->params['breadcrumbs']['View'] = '/orders/view';
\yii\web\YiiAsset::register($this);
?>
<div class="orders-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'store name',
                'value' => $storeName->name,
            ],
            'quantity',
            'total_price',
            'date',
        ],
    ]) ?>
    <div class="orders_header">ORDER ITEMS</div>
    <table class="table table-striped table-bordered order_items_table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Revenue</th>
                <th>Cost</th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach ($orderItemsTable as $key => $tab){
                ?>
                <tr>
                    <td><?=$key + 1?></td>
                    <td><?=$tab['name']?></td>
                    <td><?=$tab['quantity']?></td>
                    <td><?=$tab['price']?></td>
                    <td><?=$tab['revenue']?></td>
                    <td><?=$tab['cost']?></td>
                </tr>
                <?php
            }
        ?>

        </tbody>
    </table>

</div>
