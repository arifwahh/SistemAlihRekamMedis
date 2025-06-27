<?php
session_start(); // jika belum ada
?>
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
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Diagram Data</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">   
    

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
?>

<div>
    <canvas id="myChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Total Pasien', 'RM Aktif', 'RM Inaktif', 'Retensi', 'Musnah'],
            datasets: [{
                label: 'Jumlah',
                data: [<?php echo $pasienCount; ?>, <?php echo $rmAktifCount; ?>, <?php echo $rmInaktifCount; ?>, <?php echo $retensiCount; ?>, <?php echo $musnahCount; ?>],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 206, 86, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<?php include 'template/footer.php'; ?>