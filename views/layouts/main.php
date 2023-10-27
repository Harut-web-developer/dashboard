<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\widgets\ActiveForm;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
//$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
//$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>

<?php
session_start();
if (isset($_SESSION['username'])) {
?>

<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <?php $this->head() ?>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="/css/custom.css" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">
<?php $this->beginBody() ?>

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">STORE</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="/">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - Store -->
            <li class="nav-item">
                <a class="nav-link" href="/store">
                    <i class="fas fa-fw fa-store"></i>
                    <span>Store</span></a>
            </li>

            <!-- Nav Item - Category -->
            <li class="nav-item">
                <a class="nav-link" href="/category">
                    <i class="fas fa-fw fa-copy"></i>
                    <span>Category</span></a>
            </li>

            <!-- Nav Item - Product -->
            <li class="nav-item">
                <a class="nav-link" href="/product">
                    <i class="fas fa-fw fa-th"></i>
                    <span>Products</span></a>
            </li>

            <!-- Nav Item - Order -->
            <li class="nav-item">
                <a class="nav-link" href="/orders">
                    <i class="fas fa-fw fa-copy"></i>
                    <span>Orders</span></a>
            </li>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="/chart">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Charts</span></a>
            </li>

            <!-- Nav Item - Config -->
            <li class="nav-item">
                <a class="nav-link" href="/config">
                    <i class="fas fa-fw fa-bullseye"></i>
                    <span>Configuration</span></a>
            </li>
            <!-- <i class="fa-solid fa-screwdriver-wrench"></i> -->
            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="/target">
                    <i class="fas fa-fw fa-bullseye"></i>
                    <span>Target</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">


        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <div class="fs-search-block">

<!--                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">-->
<!--                       <form method="post" action="" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">-->
<!--                           <div class="input-group">-->
<!--                               <input type="text" class="form-control bg-light border-0 small inputval" placeholder="Search for..."-->
<!--                                      aria-label="Search" aria-describedby="basic-addon2" name="searchtable">-->
<!--                           </div>-->
<!--                       </form>-->
                        <div class="for-search">
                            <form action="" method="post" class="search searchform">


                                <input id="submit" value="" type="submit">
                                <label for="submit" class="submit"></label>


                                <a href="javascript: void(0)" class="icon"></a>

                                <input type="search" name="votrevariable" id="search" placeholder="Rechercher" class="inputval">
                            </form>
                        </div>
                        <div class="fs-search-result-wrapper shearch_menu">
                            <div class="fs-search-result-block search-res"><div class="fs-search-result-column">
                                    <h4 class="h4_product">Product</h4>
                                    <ul class="fs-search-result-column-list search-prod-list-custom parentLiProduct">

                                    </ul>
                                </div>
                                <div class="fs-search-result-column">
                                    <h4 class="h4_category">Category</h4>
                                    <ul class="fs-search-result-column-list search-prod-list-custom parentLiCategory">

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['username']; ?></span>
                                <img class="img-profile rounded-circle"
                                    src="/img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                 aria-labelledby="userDropdown">
<!--                                <a class="dropdown-item" href="#">-->
<!--                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>-->
<!--                                    Profile-->
<!--                                </a>-->
<!--                                <a class="dropdown-item" href="#">-->
<!--                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>-->
<!--                                    Settings-->
<!--                                </a>-->
<!--                                <a class="dropdown-item" href="#">-->
<!--                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>-->
<!--                                    Activity Log-->
<!--                                </a>-->
<!--                                <div class="dropdown-divider"></div>-->
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid dashboardChart">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>
                        <div class="row mb-2 bread">
                            <?php
                            $html = '';
                            if(!empty($this->params['breadcrumbs'])){
                                $i = 0;
                                foreach($this->params['breadcrumbs'] as $bread => $bread_val){

                                    if(count($this->params['breadcrumbs']) != ($i+1)){
                                    $html .= '<li class="breadcrumb-item"><a href="'.$bread_val.'">'.$bread.'</a></li>';
                                    } else {
                                    $html .= '<li class="breadcrumb-item active">'.$bread.'</li>';
                                    }
                                    $i++;
                                }
                            } else {
                                $main_title = 'Home';
                            } ?>

                            <div class="col-sm-6 ">
                                <ol class="breadcrumb float-sm-right">
                                    <?php echo $html;?>
                                </ol>
                            </div>
                        </div>
                        <?= $content ?>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->
            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="site/login">Logout</a>
            </div>
        </div>
    </div>
</div>

    <!-- Bootstrap core JavaScript-->
<!--    <script src="vendor/jquery/jquery.min.js"></script>-->
<!--    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>-->

    <!-- Core plugin JavaScript-->
<!--    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>-->

    <!-- Custom scripts for all pages-->
<!--    <script src="js/sb-admin-2.min.js"></script>-->

    <!-- Page level plugins -->
<!--    <script src="vendor/chart.js/Chart.min.js"></script>-->

    <!-- Page level custom scripts -->
<!--    <script src="js/demo/chart-area-demo.js"></script>-->
<!--    <script src="js/demo/chart-pie-demo.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.42.0/apexcharts.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.2.1/exceljs.min.js"></script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>


    <?php
}else{
    return $this->render('login');
}

?>