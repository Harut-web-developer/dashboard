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
                    <div class="card-header d-flex justify-content-between">
                        <div>
                            <div class="d-flex justify-content-start">
                                <input type="number" placeholder="Enter started number" class="form-control startId">
                                <input type="number" placeholder="Enter end number" class="form-control endId">
                            </div>
                            <?= Html::submitButton('Delete Selected Rows', ['id' => 'delete-selected', 'class' => 'btn btn-block btn-outline-dark']) ?>
                        </div>
                        <?= Html::a('Create Category', ['create'], ['class' => 'btn btn-block btn-outline-dark col-md-2', 'style' => 'margin: 31px;']) ?>

                        <?= Html::beginForm(['upload'], 'post', ['enctype' => 'multipart/form-data', 'class' => 'd-flex flex-column', 'style'=>'overflow: hidden;']) ?>
                            <input type="file" name="xlsxFile" accept=".xlsx">
                            <?= Html::submitButton('Upload XLSX', ['class' => 'btn btn-block btn-outline-dark']) ?>
                        <?= Html::endForm() ?>

                    </div>

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

                            ['class' => 'yii\grid\SerialColumn'],
//                            'id',
                            'parent_id',
                            'name',
                            [
                                'header'=>'Actions',
                                'class' => ActionColumn::className(),
                                'urlCreator' => function ($action, Category $model, $key, $index, $column) {
                                    return Url::toRoute([$action, 'id' => $model->id]);
                                 },
//                                 'buttons'=>[
//                                    'delete' => function($url,$model,$key){
//                                            return '<a href="/category/delete" title="Delete" aria-label="Delete" data-pjax="0" data-confirm="Are you sure you want to delete this item?" data-method="post"><svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:.875em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M32 464a48 48 0 0048 48h288a48 48 0 0048-48V128H32zm272-256a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zM432 32H312l-9-19a24 24 0 00-22-13H167a24 24 0 00-22 13l-9 19H16A16 16 0 000 48v32a16 16 0 0016 16h416a16 16 0 0016-16V48a16 16 0 00-16-16z"></path></svg></a>'
//                                            .'<a href="/category/delete" title="Delete" aria-label="Delete" data-pjax="0" data-confirm="Are you sure you want to delete this item?" data-method="post"><svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:.875em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M32 464a48 48 0 0048 48h288a48 48 0 0048-48V128H32zm272-256a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zM432 32H312l-9-19a24 24 0 00-22-13H167a24 24 0 00-22 13l-9 19H16A16 16 0 000 48v32a16 16 0 0016 16h416a16 16 0 0016-16V48a16 16 0 00-16-16z"></path></svg></a>';
//                                     },
//
//                                 ]

                            ],
//                            [
//                                'class' => 'yii\grid\Column',
//                                'header' => 'Lock',
//                                'contentOptions' => ['class' => 'locks'],
//                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
