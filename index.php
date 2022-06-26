<?php
session_start();
include "./config/connection.php";
if (empty($_SESSION['id'])) {
    header('location: ./auth/login.php');
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Dashboard | AHP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="/assets/coli.svg">

    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <!-- preloader -->
    <link rel="stylesheet" href="assets/css/preloader.min.css">
</head>

<body data-sidebar="dark">
    <div class="layer">
        <svg width="110" id="L7" height="110" viewBox="0 0 58 58" xmlns="http://www.w3.org/2000/svg">
            <g fill="none" fill-rule="evenodd">
                <g transform="translate(2 1)" stroke="#FFF" stroke-width="1.5">
                    <circle cx="42.601" cy="11.462" r="5" fill-opacity="1" fill="#fff">
                        <animate attributeName="fill-opacity" begin="0s" dur="1.3s" values="1;0;0;0;0;0;0;0" calcMode="linear" repeatCount="indefinite"></animate>
                    </circle>
                    <circle cx="49.063" cy="27.063" r="5" fill-opacity="0" fill="#fff">
                        <animate attributeName="fill-opacity" begin="0s" dur="1.3s" values="0;1;0;0;0;0;0;0" calcMode="linear" repeatCount="indefinite"></animate>
                    </circle>
                    <circle cx="42.601" cy="42.663" r="5" fill-opacity="0" fill="#fff">
                        <animate attributeName="fill-opacity" begin="0s" dur="1.3s" values="0;0;1;0;0;0;0;0" calcMode="linear" repeatCount="indefinite"></animate>
                    </circle>
                    <circle cx="27" cy="49.125" r="5" fill-opacity="0" fill="#fff">
                        <animate attributeName="fill-opacity" begin="0s" dur="1.3s" values="0;0;0;1;0;0;0;0" calcMode="linear" repeatCount="indefinite"></animate>
                    </circle>
                    <circle cx="11.399" cy="42.663" r="5" fill-opacity="0" fill="#fff">
                        <animate attributeName="fill-opacity" begin="0s" dur="1.3s" values="0;0;0;0;1;0;0;0" calcMode="linear" repeatCount="indefinite"></animate>
                    </circle>
                    <circle cx="4.938" cy="27.063" r="5" fill-opacity="0" fill="#fff">
                        <animate attributeName="fill-opacity" begin="0s" dur="1.3s" values="0;0;0;0;0;1;0;0" calcMode="linear" repeatCount="indefinite"></animate>
                    </circle>
                    <circle cx="11.399" cy="11.462" r="5" fill-opacity="0" fill="#fff">
                        <animate attributeName="fill-opacity" begin="0s" dur="1.3s" values="0;0;0;0;0;0;1;0" calcMode="linear" repeatCount="indefinite"></animate>
                    </circle>
                    <circle cx="27" cy="5" r="5" fill-opacity="0" fill="#fff">
                        <animate attributeName="fill-opacity" begin="0s" dur="1.3s" values="0;0;0;0;0;0;0;1" calcMode="linear" repeatCount="indefinite"></animate>
                    </circle>
                </g>
            </g>
        </svg>
    </div>
    <div id="layout-wrapper">
        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <div class="navbar-brand-box">
                        <a href="index.html" class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="assets/images/logo.svg" alt="" height="22">
                            </span>
                            <span class="logo-lg">
                                <img src="assets/images/logo-dark.png" alt="" height="17">
                            </span>
                        </a>
                        <a href="index.html" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="assets/images/logo-light.svg" alt="" height="22">
                            </span>
                            <span class="logo-lg">
                                <img src="/assets/dddddd.svg" height="90">
                            </span>
                        </a>
                    </div>
                    <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                        <i class="fa fa-fw fa-bars"></i>
                    </button>
                </div>

                <div class="d-flex">
                    <div class="dropdown d-inline-block d-lg-none ms-2">
                        <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-magnify"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-search-dropdown">
                            <form class="p-3">
                                <div class="form-group m-0">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="rounded-circle header-profile-user" src="assets/images/users/avatar-1.jpg" alt="Header Avatar">
                            <span class="d-none d-xl-inline-block ms-1" key="t-henry"><?= $_SESSION['nama_pengguna'] ?></span>
                            <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            <a class="dropdown-item" href="#"><i class="bx bx-user font-size-16 align-middle me-1"></i> <span key="t-profile">Profile</span></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="./logout.php"><i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span key="t-logout">Logout</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- ========== Left Sidebar Start ========== -->
        <div class="vertical-menu">

            <div data-simplebar class="h-100">

                <!--- Sidemenu -->
                <div id="sidebar-menu">
                    <!-- Left Menu Start -->
                    <ul class="metismenu list-unstyled" id="side-menu">
                        <li class="menu-title" key="t-menu">Menu</li>

                        <li>
                            <a href="?page=dashboard" class="waves-effect">
                                <i class="bx bx-home-circle"></i>
                                <span key="t-dashboards">Beranda</span>
                            </a>
                        </li>
                        <li class="menu-title" key="t-menu">Data</li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="bx bx-stats"></i>
                                <span key="t-layouts">Kriteria</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="true">
                                <li>
                                    <a href="?page=daftar_kriteria">Daftar Kriteria</a>
                                </li>
                                <li>
                                    <a href="?page=perbandingan_kriteria">Perbandingan</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="bx bx-font-size"></i>
                                <span key="t-layouts">Alternatif</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="true">
                                <li>
                                    <a href="?page=daftar_alternatif">Daftar Alternatif</a>

                                </li>
                                <li>
                                    <a href="javascript: void(0);" class="has-arrow" key="t-horizontal">Perbandingan</a>
                                    <ul class="sub-menu" aria-expanded="true">
                                        <?php
                                        $query = $conn->query("SELECT * FROM tb_criteria");
                                        while ($getData = mysqli_fetch_assoc($query)) :
                                        ?>
                                            <li><a href="?page=perbandingan_alternatif&sub=<?=$getData['description'] ?>&id_criteria=<?= $getData['criteria_id'] ?>" key="t-horizontal"><?=$getData['description'] ?></a></li>
                                        <?php
                                        endwhile ?>

                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="menu-title" key="t-menu">Hasil</li>
                        <li>
                            <a href="?page=lihat_hasil">
                                <i class="bx bx-analyse"></i>
                                <span key="t-layouts">Lihat Hasil</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- Sidebar -->
            </div>
        </div>
        <!-- Left Sidebar End -->
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <?php
                    @$page = $_GET['page'];
                    switch ($page) {
                        case 'dashboard':
                            include "./dashboard.php";
                            break;
                        case 'daftar_kriteria':
                            include "./kriteria/daftar_kriteria.php";
                            break;
                        case 'perbandingan_kriteria':
                            include "./kriteria/perbandingan_kriteria.php";
                            break;
                        case 'daftar_alternatif':
                            include "./alternatif/daftar_alternatif.php";
                            break;
                        case 'perbandingan_alternatif':
                            include "./alternatif/perbandingan_alternatif.php";
                            break;
                        case 'lihat_hasil':
                            include "./lihat_hasil.php";
                            break;
                        default:
                            echo "<script>
                        window.location.href = './notFound.php'
                    </script>";
                            break;
                    }

                    ?>
                </div>
            </div>
            <!-- End Page-content -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>
                                document.write(new Date().getFullYear())
                            </script> © A2.
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Design & Develop by Alfian-Anggi
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    <!-- JAVASCRIPT -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>

    <!-- apexcharts -->
    <script src="assets/libs/apexcharts/apexcharts.min.js"></script>

    <!-- Saas dashboard init -->
    <script src="assets/js/pages/saas-dashboard.init.js"></script>

    <script src="assets/js/app.js"></script>
    <script>
        window.onload = function() {
            const element = document.querySelector('.layer');
            setTimeout(() => {
                element.classList.add("d-none")
            }, 1000)
        }
    </script>

</body>

</html>