<?php
    session_start();
	if($_SESSION['status']!="login"){
		header("location:../login.php?pesan=belum_login");
	}
  // config.php atau di bagian atas file PHP
if ($_SERVER['HTTP_HOST'] == 'localhost') {
    $base_url = '/sistemalihrm';  // nama folder project di htdocs
} else {
    $base_url = 'https://sistemalihrm.awmorecreative.com';  // root domain langsung, misal https://sistemalihrm.awmorecreative.com
}
	?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistem Alih RM | Dashboard Petugas</title>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= $base_url ?>/assets/administrator/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="<?= $base_url ?>/assets/administrator/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?= $base_url ?>/assets/administrator/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="<?= $base_url ?>/assets/administrator/plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= $base_url ?>/assets/administrator/dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?= $base_url ?>/assets/administrator/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?= $base_url ?>/assets/administrator/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="<?= $base_url ?>/assets/administrator/plugins/summernote/summernote-bs4.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?= $base_url ?>/assets/administrator/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= $base_url ?>/assets/administrator/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= $base_url ?>/assets/administrator/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
  <link rel="stylesheet" href="<?= $base_url ?>/assets/css/wizard.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="<?= $base_url ?>/assets/administrator/dist/img/logobakti.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>

      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="<?= $base_url ?>/assets/administrator/dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Brad Diesel
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">Call me whenever you can...</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="<?= $base_url ?>/assets/administrator/dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  John Pierce
                  <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="<?= $base_url ?>/assets/administrator/dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Nora Silvester
                  <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="<?= $base_url ?>/assets/administrator/dist/img/logobakti.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Puskesmas Kaliwates</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <?php
          // Ambil nama file foto (tanpa ekstensi) dari database berdasarkan session pengguna
          $koneksi = mysqli_connect("localhost", "u756913646_sistemalih", "JKT48gamers?", "u756913646_sistemalih");
          if (mysqli_connect_errno()) {
            echo "Koneksi database gagal: " . mysqli_connect_error();
          }
          $id_pengguna = $_SESSION['idpengguna'];
          $query_foto = "SELECT foto_pengguna FROM pengguna WHERE id_pengguna = '$id_pengguna' LIMIT 1";
          $result_foto = mysqli_query($koneksi, $query_foto);
          $data_foto = mysqli_fetch_assoc($result_foto);
          $foto_nama = !empty($data_foto['foto_pengguna']) ? pathinfo($data_foto['foto_pengguna'], PATHINFO_FILENAME) : '';
          $foto_path = '';
          $serverfoto = $_SERVER['DOCUMENT_ROOT'] . 'assets/fotopengguna/';
          $foto_dir = $serverfoto;
          $foto_url = $base_url . '/assets/fotopengguna/';
          $foto_found = false;

          if ($foto_nama !== '') {
              // Cari file dengan nama yang sama (tanpa ekstensi) di folder assets/fotopengguna
              foreach (glob($foto_dir . $foto_nama . '.*') as $file) {
            $foto_path = $foto_url . basename($file);
            $foto_found = true;
            break;
              }
          }

          if (!$foto_found) {
              $foto_path = $base_url . '/assets/fotopengguna/fotodefault.png';
          }
          ?>
          <img src="<?= htmlspecialchars($foto_path) ?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $_SESSION['nama_pengguna'];?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="home.php" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                BERANDA
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-user"></i>
              <p>
                MASTER DATA
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">6</span>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="datapengguna.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Data Pengguna</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="datapasien.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pasien dan Kunjungan</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-folder"></i>
              <p>
                REKAM MEDIS
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="rmaktif.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                          RM Active
                          <?php 
                          $koneksi = mysqli_connect("localhost", "u756913646_sistemalih", "JKT48gamers?", "u756913646_sistemalih");

                          if (mysqli_connect_errno()) {
                              echo "Koneksi database gagal: " . mysqli_connect_error();
                          }

                          $query = "SELECT COUNT(*) AS total 
              FROM (
                  SELECT a.id_pasien
                  FROM pasien a
                  INNER JOIN kunjungan b ON a.id_pasien = b.id_pasien
                  INNER JOIN rm c ON a.id_pasien = c.id_pasien
                  WHERE c.status = '-'
                  GROUP BY a.id_pasien
                  HAVING MAX(b.tanggal_kunjungan) >= DATE_SUB(CURDATE(), INTERVAL 2 YEAR)
              ) AS pasien_lama;
              ";

                          $result = mysqli_query($koneksi, $query);
                          $data = mysqli_fetch_assoc($result);
                          $totalInactive = $data['total'];
                          ?>

                          <span class="right badge badge-danger"><?php echo $totalInactive; ?></span>
                      </p>
                </a>
              </li>
                <li class="nav-item">
                  <a href="rminaktif.php" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>
                          RM In-Active
                          <?php 
                          $koneksi = mysqli_connect("localhost", "u756913646_sistemalih", "JKT48gamers?", "u756913646_sistemalih");

                          if (mysqli_connect_errno()) {
                              echo "Koneksi database gagal: " . mysqli_connect_error();
                          }

                          $query = "SELECT COUNT(*) AS total 
              FROM (
                  SELECT a.id_pasien
                  FROM pasien a
                  INNER JOIN kunjungan b ON a.id_pasien = b.id_pasien
                  INNER JOIN rm c ON a.id_pasien = c.id_pasien
                  WHERE c.status = '-'
                  GROUP BY a.id_pasien
                  HAVING MAX(b.tanggal_kunjungan) < DATE_SUB(CURDATE(), INTERVAL 2 YEAR)
              ) AS pasien_lama;
              ";

                          $result = mysqli_query($koneksi, $query);
                          $data = mysqli_fetch_assoc($result);
                          $totalInactive = $data['total'];
                          ?>

                          <span class="right badge badge-danger"><?php echo $totalInactive; ?></span>
                      </p>
                  </a>
              </li>
              <li class="nav-item">
                <a href="rmretensi.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                Retensi
                <?php
                // Koneksi database (gunakan variabel $koneksi jika sudah ada)
                if (!isset($koneksi)) {
                  $koneksi = mysqli_connect("localhost", "u756913646_sistemalih", "JKT48gamers?", "u756913646_sistemalih");
                  if (mysqli_connect_errno()) {
                    echo "Koneksi database gagal: " . mysqli_connect_error();
                  }
                }
                $query_musnah = "SELECT COUNT(*) AS total FROM rm WHERE status = 'RETENSI'";
                $result_musnah = mysqli_query($koneksi, $query_musnah);
                $data_musnah = mysqli_fetch_assoc($result_musnah);
                $totalMusnah = $data_musnah['total'];
                ?>
                <span class="right badge badge-danger"><?php echo $totalMusnah; ?></span>
              </p>
                </a>
              </li>
                  <li class="nav-item">
                <a href="rmmusnah.php" class="nav-link">
                   <i class="far fa-circle nav-icon"></i>
                  <p>
                    Telah Musnah
                    <?php
                    // Koneksi database (gunakan variabel $koneksi jika sudah ada)
                    if (!isset($koneksi)) {
                      $koneksi = mysqli_connect("localhost", "u756913646_sistemalih", "JKT48gamers?", "u756913646_sistemalih");
                      if (mysqli_connect_errno()) {
                        echo "Koneksi database gagal: " . mysqli_connect_error();
                      }
                    }
                    $query_musnah = "SELECT COUNT(*) AS total FROM rm WHERE status = 'MUSNAH'";
                    $result_musnah = mysqli_query($koneksi, $query_musnah);
                    $data_musnah = mysqli_fetch_assoc($result_musnah);
                    $totalMusnah = $data_musnah['total'];
                    ?>
                    <span class="right badge badge-danger"><?php echo $totalMusnah; ?></span>
                  </p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="beritaacara.php" class="nav-link">
              <i class="nav-icon fas fa-file"></i>
              <p>
                BERITA ACARA
                <?php
                // Koneksi database (gunakan variabel $koneksi jika sudah ada)
                if (!isset($koneksi)) {
                  $koneksi = mysqli_connect("localhost", "u756913646_sistemalih", "JKT48gamers?", "u756913646_sistemalih");
                  if (mysqli_connect_errno()) {
                    echo "Koneksi database gagal: " . mysqli_connect_error();
                  }
                }
                $query_berita = "SELECT COUNT(*) AS total FROM berita_acara";
                $result_berita = mysqli_query($koneksi, $query_berita);
                $data_berita = mysqli_fetch_assoc($result_berita);
                $totalBerita = $data_berita['total'];
                ?>
                <span class="right badge badge-danger"><?php echo $totalBerita; ?></span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="laporan.php" class="nav-link">
              <i class="nav-icon fas fa-trash"></i>
              <p>
                GENERATE LAPORAN
                <span class="right badge badge-danger">-</span>
              </p>
            </a>
          </li>
          <li class="nav-header">-----------------------------------------</li>
          <li class="nav-item">
            <a href="iframe.html" class="nav-link">
              <i class="nav-icon fas fa-user-circle"></i>
              <p>PROFILE</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="template/logout.php" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>KELUAR</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
