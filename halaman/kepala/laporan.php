<?php include 'template/header.php'; ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Laporan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="home.php">Beranda</a></li>
                        <li class="breadcrumb-item active">Laporan</li>
                    </ol>
                </div>  
            </div>
        </div><!-- /.container-fluid -->
    </section>          
<div class="container mt-4">
    <div class="row">
        <!-- Card 1: Laporan Rekam Medis Musnah -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    Laporan Rekam Medis Musnah
                </div>
                <div class="card-body">
                    <form action="../../proses/generate_laporan_musnah.php" method="get" target="_blank">
                        <div class="mb-3">
                            <label for="tanggal_awal_musnah" class="form-label">Tanggal Awal</label>
                            <input type="date" class="form-control" id="tanggal_awal_musnah" name="tanggal_awal" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_akhir_musnah" class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="tanggal_akhir_musnah" name="tanggal_akhir" required>
                        </div>
                        <button type="submit" class="btn btn-danger">Generate</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Card 2: Laporan Retensi -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Laporan Retensi
                </div>
                <div class="card-body">
                    <form action="../../proses/generate_laporan_retensi.php" method="get" target="_blank">
                        <div class="mb-3">
                            <label for="tanggal_awal_retensi" class="form-label">Tanggal Awal</label>
                            <input type="date" class="form-control" id="tanggal_awal_retensi" name="tanggal_awal" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_akhir_retensi" class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="tanggal_akhir_retensi" name="tanggal_akhir" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Generate</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php include 'template/footer.php'; ?>