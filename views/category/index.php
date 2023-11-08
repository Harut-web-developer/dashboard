<?php

use app\models\Category;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\CategorySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Categories';
$this->params['breadcrumbs']['Home'] ='/';
$this->params['breadcrumbs']['Category'] = '/category/index';
?>
<div class="category-index">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header row d-flex justify-content-between">
                        <div class="col-xl-3 col-sm-5 col-xs-3 mb-4">
                            <div class="d-flex justify-content-start">
                                <input type="number" placeholder="Started number" class="form-control startId">
                                <input type="number" placeholder="End number" class="form-control endId">
                            </div>
                            <?= Html::submitButton('Delete Selected Rows', ['id' => 'delete-selected', 'class' => 'btn btn-block btn-outline-dark']) ?>
                        </div>
                        <div class=" col-xl-3 col-sm-3 col-xs-3 mb-4 d-flex align-items-end">
                            <?= Html::a('Create Category', ['create'], ['class' => 'btn btn-block btn-outline-dark', 'style' => 'margin: 0px;']) ?>
                        </div>
                        <?= Html::beginForm(['upload'], 'post', ['enctype' => 'multipart/form-data', 'class' => 'd-flex flex-column col-xl-3 col-xs-3 col-sm-3 mt-2', 'style'=>'overflow: hidden;']) ?>
                            <input type="file" name="xlsxFile" accept=".xlsx">
                            <?= Html::submitButton('Upload XLSX', ['class' => 'btn btn-block btn-outline-dark']) ?>
                        <?= Html::endForm() ?>

                    </div>
                    <title><?= Html::encode($this->title); ?></title>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
//                        'filterModel' => $searchModel,
                        'columns' => [
                            [
                                'class' => 'yii\grid\CheckboxColumn',
                                'name' => 'selection[]',
                                'header' =>  Html::checkBox('select-all-checkbox', false, [
                                        'class' => 'select-all-checkbox',
                                        'label' => 'Select All',
                                    ]
                                ),
                            ],
                            'id',
                            'name',
                            [
                                'header'=>'Actions',
                                'class' => ActionColumn::className(),
                                'urlCreator' => function ($action, Category $model, $key, $index, $column) {
                                    return Url::toRoute([$action, 'id' => $model->id]);
                                 },
                            ],

                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
