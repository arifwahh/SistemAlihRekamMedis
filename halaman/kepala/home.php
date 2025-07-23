<?php include 'template/header.php'; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Selamat Datang</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="home.php">Beranda</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- <div class="col-md-12">
                    <p><button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-xl">Tambah
                            Pasien</button>
                            <a href="../../proses/eksportxls.php" target="_blank" class="btn btn-success"><i class="far fa-print"></i> &nbsp Export Excel</a></p>
                </div> -->
            </div>
            <div class="row">
                <?php
                // Database connection
                $conn = new mysqli("localhost", "u756913646_sistemalih", "JKT48gamers?", "u756913646_sistemalih");

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch counts from the database
                $pasienCount = $conn->query("SELECT COUNT(*) AS count FROM pasien")->fetch_assoc()['count'];
                $rmAktifCount = $conn->query("SELECT COUNT(DISTINCT a.id_pasien) AS count FROM pasien a JOIN kunjungan b ON a.id_pasien = b.id_pasien WHERE b.tanggal_kunjungan > DATE_SUB(CURDATE(), INTERVAL 2 YEAR)")->fetch_assoc()['count'];
                $rmInaktifCount = $conn->query("SELECT COUNT(DISTINCT a.id_pasien) AS count 
                                                FROM pasien a 
                                                JOIN kunjungan b ON a.id_pasien = b.id_pasien 
                                                JOIN rm c ON a.id_pasien = c.id_pasien 
                                                WHERE b.tanggal_kunjungan < DATE_SUB(CURDATE(), INTERVAL 2 YEAR) AND c.status = '-'")->fetch_assoc()['count'];
                $retensiCount = $conn->query("SELECT COUNT(DISTINCT a.id_pasien) AS count 
                                              FROM pasien a 
                                              JOIN kunjungan b ON a.id_pasien = b.id_pasien 
                                              JOIN rm c ON a.id_pasien = c.id_pasien 
                                              WHERE b.tanggal_kunjungan < DATE_SUB(CURDATE(), INTERVAL 2 YEAR) AND c.status = 'RETENSI'")->fetch_assoc()['count'];
                $musnahCount = $conn->query("SELECT COUNT(DISTINCT a.id_pasien) AS count 
                                              FROM pasien a 
                                              JOIN kunjungan b ON a.id_pasien = b.id_pasien 
                                              JOIN rm c ON a.id_pasien = c.id_pasien 
                                              WHERE b.tanggal_kunjungan < DATE_SUB(CURDATE(), INTERVAL 2 YEAR) AND c.status = 'MUSNAH'")->fetch_assoc()['count'];

                // Tambahan kategori baru
                $totalRmCount = $conn->query("SELECT COUNT(*) AS count FROM rm")->fetch_assoc()['count'];
                $totalPenggunaCount = $conn->query("SELECT COUNT(*) AS count FROM pengguna")->fetch_assoc()['count'];
                $totalBeritaAcaraCount = $conn->query("SELECT COUNT(*) AS count FROM berita_acara")->fetch_assoc()['count'];
                ?>

                <div class="col-lg-3 col-6">
                    <div class="card bg-info">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <span><b>Total Pasien</b></span>
                            <span style="font-size: 1.5rem; font-weight: bold;"><?php echo $pasienCount; ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="card bg-success">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <span><b>RM Aktif</b></span>
                            <span style="font-size: 1.5rem; font-weight: bold;"><?php echo $rmAktifCount; ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="card bg-warning">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <span><b>RM Inaktif</b></span>
                            <span style="font-size: 1.5rem; font-weight: bold;"><?php echo $rmInaktifCount; ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="card bg-primary">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <span><b>Retensi</b></span>
                            <span style="font-size: 1.5rem; font-weight: bold;"><?php echo $retensiCount; ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="card bg-danger">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <span><b>Musnah</b></span>
                            <span style="font-size: 1.5rem; font-weight: bold;"><?php echo $musnahCount; ?></span>
                        </div>
                    </div>
                </div>
                <!-- Card tambahan -->
                <div class="col-lg-3 col-6">
                    <div class="card bg-secondary">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <span><b>Total RM</b></span>
                            <span style="font-size: 1.5rem; font-weight: bold;"><?php echo $totalRmCount; ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="card bg-teal">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <span><b>Total Pengguna</b></span>
                            <span style="font-size: 1.5rem; font-weight: bold;"><?php echo $totalPenggunaCount; ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="card" style="background-color: #6f42c1; color: #fff;">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <span><b>Total Berita Acara</b></span>
                            <span style="font-size: 1.5rem; font-weight: bold;"><?php echo $totalBeritaAcaraCount; ?></span>
                        </div>
                    </div>
                </div>
                <!-- End Card tambahan -->
            </div>
        </div>
    </section>
    <section class="content mt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h3 class="card-title">Diagram Alir Data Pasien</h3>
                        </div>
                        <div class="card-body">
                            <!-- Tambahkan style width dan height pada canvas agar chart tidak mengecil -->
                            <canvas id="pasienChart" style="width:100% !important; height:350px !important; max-width:100%;" height="350"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Atur ukuran canvas secara eksplisit agar chart tidak mengecil
        const ctx = document.getElementById('pasienChart').getContext('2d');
        const pasienChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [
                    'Total Pasien',
                    'RM Aktif',
                    'RM Inaktif',
                    'Retensi',
                    'Musnah',
                    'Total RM',
                    'Total Pengguna',
                    'Total Berita Acara'
                ],
                datasets: [{
                    label: 'Jumlah',
                    data: [
                        <?php echo $pasienCount; ?>,
                        <?php echo $rmAktifCount; ?>,
                        <?php echo $rmInaktifCount; ?>,
                        <?php echo $retensiCount; ?>,
                        <?php echo $musnahCount; ?>,
                        <?php echo $totalRmCount; ?>,
                        <?php echo $totalPenggunaCount; ?>,
                        <?php echo $totalBeritaAcaraCount; ?>
                    ],
                    backgroundColor: [
                        'rgba(23, 162, 184, 0.2)',
                        'rgba(40, 167, 69, 0.2)',
                        'rgba(255, 193, 7, 0.2)',
                        'rgba(0, 123, 255, 0.2)',
                        'rgba(220, 53, 69, 0.2)',
                        'rgba(108, 117, 125, 0.2)',
                        'rgba(32, 201, 151, 0.2)',
                        'rgba(248, 249, 250, 0.2)'
                    ],
                    borderColor: [
                        'rgba(23, 162, 184, 1)',
                        'rgba(40, 167, 69, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(0, 123, 255, 1)',
                        'rgba(220, 53, 69, 1)',
                        'rgba(108, 117, 125, 1)',
                        'rgba(32, 201, 151, 1)',
                        'rgba(248, 249, 250, 1)'
                    ],
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: [
                        'rgba(23, 162, 184, 1)',
                        'rgba(40, 167, 69, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(0, 123, 255, 1)',
                        'rgba(220, 53, 69, 1)',
                        'rgba(108, 117, 125, 1)',
                        'rgba(32, 201, 151, 1)',
                        'rgba(108, 117, 125, 1)'
                    ],
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Tambahkan ini agar chart mengikuti tinggi yang diatur
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });
    </script>
</div>

<?php include 'template/footer.php'; ?>