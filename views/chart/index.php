<?php
use yii\helpers\Html;

$this->title = 'Chart';
$this->params['breadcrumbs']['Home'] ='/';
$this->params['breadcrumbs']['Chart'] = '/chart/index';
?>


<div class="filter form-group">
    <select class="store form-control col-lg-2 col-md-3 col-sm-3 mr-2 ml-2 mb-4">
        <option  value="null">--choose store--</option>
        <?php
        foreach ($stores as $store){
         ?>
            <option value="<?=$store['id']?>"><?=$store['name']?></option>
        <?php
        }
        ?>
    </select>
    <select class="category form-control col-lg-2 col-md-3 col-sm-3 mr-2 ml-2 mb-4">
        <option value="0">--choose category--</option>
        <?php
        foreach ($categories as $category){
            ?>
            <option value="<?=$category['id']?>"><?=$category['name']?></option>
            <?php
        }
        ?>
    </select>
    <input class="start_date form-control col-lg-2 col-md-3 col-sm-3 mr-2 ml-2 mb-4" value="<?=date('Y-m-01')?>" type="date">
    <input class="end_date form-control col-lg-2 col-md-3 col-sm-3 mr-2 ml-2 mb-4" value="<?=date('Y-m-d')?>" type="date">
    <select class="pay form-control col-lg-2 col-md-3 col-sm-3 mr-2 ml-2 mb-4">
        <option value="">--choose payment type--</option>
        <option value="cash">cash</option>
        <option value="card">card</option>
    </select>

    <div class="text-center" onclick="printContent('print')">
        <img width="35" height="35" class="printicon" src="https://img.icons8.com/carbon-copy/100/000000/print.png" alt="print"/>
    </div>

</div>

<div id="print">
    <title><?= Html::encode($this->title); ?></title>
    <div class="row">

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            more expensive selling product</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 productName"></div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 productPrice"></div>
                    </div>
                    <div class="col-auto prod">
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                more selling product</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 maxCountProductName"></div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 productMaxCount"></div>
                    </div>
                        <div class="col-auto prodCount">
                          <img class="productCountImg myimg" src="">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">average check, %
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800 orderProcent"></div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info procentBar" role="progressbar" style="" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                total number of checks</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 ordersCount"></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <!-- Area Chart -->
        <div class="col-xl-8 col-lg-7">
            <div id="chart"></div>
        </div>

    <!-- Pie Chart -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Revenue Sources</h6>
<!--                <div class="dropdown no-arrow">-->
<!--                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
<!--                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>-->
<!--                    </a>-->
<!--                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">-->
<!--                        <div class="dropdown-header">Dropdown Header:</div>-->
<!--                        <a class="dropdown-item" href="#">Action</a>-->
<!--                        <a class="dropdown-item" href="#">Another action</a>-->
<!--                        <div class="dropdown-divider"></div>-->
<!--                        <a class="dropdown-item" href="#">Something else here</a>-->
<!--                    </div>-->
<!--                </div>-->
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                    <canvas id="myPieChart" width="447" height="306" style="display: block; height: 245px; width: 358px;" class="chartjs-render-monitor"></canvas>
                </div>
                <div class="mt-4 text-center small">
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-primary"></i> Sells
                                        </span>
                    <span class="mr-2">
                                            <i class="fas fa-circle text-success"></i> Revenue
                                        </span>
                    <span class="mr-2">
                                            <i class="fas fa-circle text-info"></i> Target revenue
                                        </span>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Logout Modal-->
<!--<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"-->
<!--    aria-hidden="true">-->
<!--    <div class="modal-dialog " role="document">-->
<!--        <div class="modal-content">-->
<!--            <div class="modal-header bg-gradient-primary">-->
<!--                <button class="btn-close" type="button" data-dismiss="modal" aria-label="Close">-->
<!--                </button>-->
<!--            </div>-->
<!--            <div class="modal-body">-->
<!--                <img class="img-thumbnail" src="">-->
<!--            </div>-->
<!--            <div class="modal-footer bg-gradient-primary">-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->


<?php
$this->registerJsFile(
'@web/js/custom.js',
['depends' => [\yii\web\JqueryAsset::class]]
);
?>

<!--Print-->
<!--<div class="text-center" onclick="  window.print()">-->
<!--    <img width="35" height="35" class="printicon" src="https://img.icons8.com/carbon-copy/100/000000/print.png" alt="print"/>-->
<!--</div>-->


