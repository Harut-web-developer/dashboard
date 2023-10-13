<?php

use app\models\Product;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ProductSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Products';
$this->params['breadcrumbs']['Home'] ='/';
$this->params['breadcrumbs']['Products'] = '/product/index';
?>
<?php //if(!isset($data_size)){ ?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Product', ['create'], ['class' => 'btn btn-block btn-outline-dark col-md-2 btn-sm']) ?>
    </p>

    <!--Download XLSX-->
    <button class="downloadXLSX" >Download XLSX</button>

    <?php $dataProvider->pagination = false; ?>
    <?= GridView::widget([

        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
//            'id',
            'category_id',
            'name',
            'description:ntext',
            'price',
            'cost',
            [

                'attribute' => 'img',
                'format' => 'raw',
                'value' => function($model){
<<<<<<< .merge_file_GLfIAn
                    return '<img src="/uploads/'.$model->img.'"width="50">';

=======
                    return '<img src="/web/uploads/'.$model->img.'"width="50">';
>>>>>>> .merge_file_ar8h0b
//                    return Html::img(Yii::getAlias('/web/uploads/'). $model->img,[
//                        'alt'=>$model->img,
//                    ]);
                },
            ],
            [
                'header'=>'Actions',
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Product $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>
<?php
//}
//else{ ?>
<!--    --><?php //$dataProvider->pagination = false; ?>
<!--    --><?php //= GridView::widget([
//        'tableOptions' => [
//            'class'=>'table table-striped table-bordered chatgbti_ exelgenerate',
//        ],
//        'dataProvider' => $dataProvider,
////        'filterModel' => $searchModel,
//        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
////            'id',
//            'category_id',
//            'name',
//            'description:ntext',
//            'price',
//            'cost',
//            [
//
//                'attribute' => 'img',
//                'format' => 'raw',
//                'value' => function($model){
//                    return '<img src="/web/uploads/'.$model->img.'"width="50">';
////                    return Html::img(Yii::getAlias('/web/uploads/'). $model->img,[
////                        'alt'=>$model->img,
////                    ]);
//                },
//            ],
//            [
//                'header'=>'Actions',
//                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, Product $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'id' => $model->id]);
//                }
//            ],
//        ],
//    ]); ?>
<!---->
<?php //} ?>

</div>