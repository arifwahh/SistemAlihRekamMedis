<?php include 'template/header.php'; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>DATA PASIEN</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="home.php">Beranda</a></li>
                        <li class="breadcrumb-item active">Data Pasien</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <p>
                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-xl">Tambah Pasien</button>
                        <a href="../../proses/eksportxls.php" target="_blank" class="btn btn-success"><i class="far fa-print"></i> &nbsp Export Excel</a>
                        <!-- Tombol Import Excel -->
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#importExcelModal">
                            <i class="fa fa-upload"></i> Import Excel
                        </button>
                    </p>
                </div>

                <!-- Modal Import Excel -->
                <div class="modal fade" id="importExcelModal" tabindex="-1" role="dialog" aria-labelledby="importExcelModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form action="../../proses/importxls.php" method="post" enctype="multipart/form-data">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="importExcelModalLabel">Import Data Pasien dari Excel</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="alert alert-info">
                                        <b>Format Excel harus sesuai urutan dan kolom berikut:</b>
                                        <ul style="margin-bottom:0">
                                            <li>NIK</li>
                                            <li>Nama Pasien</li>
                                            <li>Nama Kepala Keluarga Pasien</li>
                                            <li>Jenis Kelamin (Laki Laki/Perempuan)</li>
                                            <li>Pekerjaan</li>
                                            <li>Tanggal Lahir (YYYY-MM-DD)</li>
                                            <li>Agama</li>
                                            <li>Alamat</li>
                                            <li>Tanggal Kunjungan (YYYY-MM-DD, pisahkan koma jika lebih dari satu)</li>
                                            <li>Diagnosa (pisahkan koma jika lebih dari satu)</li>
                                            <li>Poli (pisahkan koma jika lebih dari satu)</li>
                                            <li>Klinik (pisahkan koma jika lebih dari satu)</li>
                                            <li>Biaya (BPJS/Umum, pisahkan koma jika lebih dari satu)</li>
                                            <li>No BPJS (pisahkan koma jika lebih dari satu)</li>
                                            <li>No Rekam Medis</li>
                                            <!-- File PDF tidak diimport via excel -->
                                        </ul>
                                        <a href="../../assets/template_import_pasien.xlsx" class="btn btn-link">Download Template Excel</a>
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="file_excel">Pilih File Excel (.xlsx, .xls):</label>
                                        <input type="file" name="file_excel" id="file_excel" class="form-control" accept=".xlsx,.xls" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Import</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Pasien</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
    <!-- Fitur Pencarian -->
    <form method="get" class="form-inline mb-3">
        <div class="form-group mr-2">
            <select name="kategori" class="form-control">
                <option value="no_rm" <?= (isset($_GET['kategori']) && $_GET['kategori'] == 'no_rm') ? 'selected' : '' ?>>No RM</option>
                <option value="nik_pasien" <?= (isset($_GET['kategori']) && $_GET['kategori'] == 'nik_pasien') ? 'selected' : '' ?>>NIK</option>
                <option value="nama_pasien" <?= (isset($_GET['kategori']) && $_GET['kategori'] == 'nama_pasien') ? 'selected' : '' ?>>Nama Pasien</option>
                <option value="tanggal_lahir_pasien" <?= (isset($_GET['kategori']) && $_GET['kategori'] == 'tanggal_lahir_pasien') ? 'selected' : '' ?>>Tanggal Lahir</option>
                <option value="jenis_kelamin_pasien" <?= (isset($_GET['kategori']) && $_GET['kategori'] == 'jenis_kelamin_pasien') ? 'selected' : '' ?>>Gender</option>
                <option value="alamat_pasien" <?= (isset($_GET['kategori']) && $_GET['kategori'] == 'alamat_pasien') ? 'selected' : '' ?>>Alamat</option>
            </select>
        </div>
        <div class="form-group mr-2">
            <input type="text" name="keyword" class="form-control" placeholder="Cari..." value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
        </div>
        <button type="submit" class="btn btn-primary">Cari</button>
        <a href="datapasien.php" class="btn btn-secondary ml-2">Reset</a>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>No RM</th>
                <th>NIK</th>
                <th>Nama Pasien</th>
                <th>Tanggal Lahir</th>
                <th>Gender</th>
                <th>Alamat</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            include '../../proses/koneksi.php';
            include '../../proses/tanggalindo.php';

            // Set limit dan offset
            $limit = 5;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $offset = ($page - 1) * $limit;

            // Pencarian
            $where = "";
            $join_rm = false;
            if (!empty($_GET['kategori']) && !empty($_GET['keyword'])) {
                $kategori = $_GET['kategori'];
                $keyword = mysqli_real_escape_string($koneksi, $_GET['keyword']);
                if ($kategori == 'no_rm') {
                    // Join ke tabel rm
                    $join_rm = true;
                    $where = "WHERE rm.no_rm LIKE '%$keyword%'";
                } else {
                    $where = "WHERE $kategori LIKE '%$keyword%'";
                }
            }

            // Hitung total data untuk pagination
            if ($join_rm) {
                $count_sql = "SELECT COUNT(DISTINCT pasien.id_pasien) as total FROM pasien 
                              LEFT JOIN rm ON pasien.id_pasien = rm.id_pasien $where";
            } else {
                $count_sql = "SELECT COUNT(*) as total FROM pasien $where";
            }
            $result = mysqli_query($koneksi, $count_sql);
            $total_rows = mysqli_fetch_assoc($result)['total'];
            $total_pages = ceil($total_rows / $limit);

            // Query data pasien
            if ($join_rm) {
                $sql = "SELECT pasien.* FROM pasien 
                        LEFT JOIN rm ON pasien.id_pasien = rm.id_pasien 
                        $where 
                        GROUP BY pasien.id_pasien 
                        LIMIT $limit OFFSET $offset";
            } else {
                $sql = "SELECT * FROM pasien $where LIMIT $limit OFFSET $offset";
            }
            $data = mysqli_query($koneksi, $sql);
            $no = $offset + 1;

            while ($d = mysqli_fetch_array($data)) { 
                $idpasien = $d['id_pasien'];
                
                // PERBAIKAN: Inisialisasi variabel sebelum digunakan
                $rekamid = '';
                $linkrm = '';
                
                // Query untuk mendapatkan data rekam medis
                $rekam = mysqli_query($koneksi, "SELECT * FROM rm WHERE id_pasien = '$idpasien'");
                
                // PERBAIKAN: Cek apakah ada data rekam medis
                if (mysqli_num_rows($rekam) > 0) {
                    $tampilrekam = mysqli_fetch_array($rekam);
                    // PERBAIKAN: Cek apakah array key ada sebelum mengakses
                    $rekamid = isset($tampilrekam['no_rm']) ? $tampilrekam['no_rm'] : '';
                    $linkrm = isset($tampilrekam['file_rm']) ? $tampilrekam['file_rm'] : '';
                }
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $rekamid; ?></td>
                    <td><?php echo $d['nik_pasien']; ?></td>
                    <td><?php echo $d['nama_pasien']; ?></td>
                    <td><?php echo tgl_indo(date($d['tanggal_lahir_pasien'])); ?></td>
                    <td><?php echo $d['jenis_kelamin_pasien']; ?></td>
                    <td><?php echo $d['alamat_pasien']; ?></td>
                    <td><?php
                        $rm_check = mysqli_query($koneksi, "SELECT * FROM rm WHERE id_pasien = '$idpasien'");
                        if (mysqli_num_rows($rm_check) > 0) {
                            $rm_data = mysqli_fetch_assoc($rm_check);
                            if ($rm_data['status'] == 'MUSNAH') {
                                echo "<span class='badge badge-danger'>MUSNAH</span>";
                            } 
                            else if ($rm_data['status'] == 'RETENSI') {
                                echo "<span class='badge badge-warning'>RETENSI</span>";
                            }
                            else if ($rm_data['status'] == '-'){
                                $kunjungan = mysqli_query($koneksi, "select MAX(tanggal_kunjungan) as knj from kunjungan where id_pasien = '$idpasien'");
                                        while ($tampilkunjungan = mysqli_fetch_array($kunjungan)) { 
                                            $tanggalKunjungan = $tampilkunjungan['knj'];
                                            $tanggalSekarang = date('Y-m-d');
                                            $datetime1 = new DateTime($tanggalKunjungan);
                                            $datetime2 = new DateTime($tanggalSekarang);
                                            $interval = $datetime1->diff($datetime2);
                                            $differenceInDays = $interval->days;
                                            if ($differenceInDays > 730) {
                                                echo "<span class='badge badge-danger'>INAKTIF</span>";
                                            } else {
                                                echo "<span class='badge badge-success'>AKTIF</span>";
                                            }
                                        }
                            }
                        }
                        else {
                            echo "<span class='badge badge-warning'>Belum Memiliki Rekam Medis</span>";
                        }
                    ?></td> 
                    <td>
                        <?php if (!empty($rekamid)): ?>
                            <a class="far fa-folder" data-toggle="modal" data-target="#mm<?=$rekamid;?>"></a>&nbsp;
                        <?php endif; ?>
                        <a class="far fa-edit" data-toggle="modal" data-target="#edit<?=$idpasien;?>"></a>&nbsp;
                        <a class="fas fa-trash" onclick="return confirm('Yakin Hapus?')" href="../../proses/hapuspasien.php?id=<?php echo $idpasien; ?>"></a>

                       <!-- Modal Edit Pasien -->
<div class="modal fade" id="edit<?= $idpasien; ?>" tabindex="-1" role="dialog" aria-labelledby="editPasienLabel<?= $idpasien; ?>" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <form action="../../proses/update_pasienall.php" method="post" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPasienLabel<?= $idpasien; ?>">Edit Data Pasien</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_pasien" value="<?= $d['id_pasien']; ?>">
                    <ul class="nav nav-tabs" id="editTab<?= $idpasien; ?>" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="info-tab<?= $idpasien; ?>" data-toggle="tab" href="#info<?= $idpasien; ?>" role="tab">Informasi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pasien-tab<?= $idpasien; ?>" data-toggle="tab" href="#pasien<?= $idpasien; ?>" role="tab">Data Pasien</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="kunjungan-tab<?= $idpasien; ?>" data-toggle="tab" href="#kunjungan<?= $idpasien; ?>" role="tab">Data Kunjungan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="rm-tab<?= $idpasien; ?>" data-toggle="tab" href="#rm<?= $idpasien; ?>" role="tab">Upload Rekam Medis</a>
                        </li>
                    </ul>
                    <div class="tab-content pt-3" id="editTabContent<?= $idpasien; ?>">
                        <!-- Tab 1: Informasi -->
                        <div class="tab-pane fade show active" id="info<?= $idpasien; ?>" role="tabpanel">
                            <h4>Informasi Pengisian</h4>
                            <p>Edit data pasien, kunjungan, dan rekam medis pada tab berikutnya.</p>
                        </div>
                        <!-- Tab 2: Data Pasien -->
                        <div class="tab-pane fade" id="pasien<?= $idpasien; ?>" role="tabpanel">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>NIK</label>
                                        <input type="number" name="nik_pasien" class="form-control" value="<?= htmlspecialchars($d['nik_pasien']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Pasien</label>
                                        <input type="text" name="nama_pasien" class="form-control" value="<?= htmlspecialchars($d['nama_pasien']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Kepala Keluarga Pasien</label>
                                        <input type="text" name="nama_kk_pasien" class="form-control" value="<?= htmlspecialchars($d['nama_kk_pasien']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Jenis Kelamin</label>
                                        <select class="form-control" name="jenis_kelamin_pasien" required>
                                            <option value="Laki Laki" <?= ($d['jenis_kelamin_pasien'] == 'Laki Laki') ? 'selected' : ''; ?>>Laki Laki</option>
                                            <option value="Perempuan" <?= ($d['jenis_kelamin_pasien'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Pekerjaan</label>
                                        <input type="text" name="pekerjaan_pasien" class="form-control" value="<?= htmlspecialchars($d['pekerjaan_pasien']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir_pasien" class="form-control" value="<?= htmlspecialchars($d['tanggal_lahir_pasien']); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Agama</label>
                                        <input type="text" name="agama_pasien" class="form-control" value="<?= htmlspecialchars($d['agama_pasien']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>No BPJS</label>
                                        <input type="text" name="nobpjs_pasien" id="nobpjs_pasien_<?= $idpasien; ?>" class="form-control nobpjs-pasien" 
                                               value="<?= htmlspecialchars($d['no_bpjs_pasien'] ?? ''); ?>" 
                                               placeholder="No BPJS (Kosongi jika Umum)">
                                    </div>
                                    <div class="form-group">
                                        <label>Alamat</label>
                                        <textarea name="alamat_pasien" class="form-control"><?= htmlspecialchars($d['alamat_pasien']); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Tab 3: Data Kunjungan -->
                        <div class="tab-pane fade" id="kunjungan<?= $idpasien; ?>" role="tabpanel">
                            <?php
                            // Ambil data kunjungan pasien
                            $kunjungan = mysqli_query($koneksi, "SELECT * FROM kunjungan WHERE id_pasien = '$idpasien' ORDER BY tanggal_kunjungan ASC");
                            ?>
                            <div class="form-group">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="edit_dynamic_field<?= $idpasien; ?>">
                                        <thead>
                                            <tr>
                                                <th>Tanggal Kunjungan</th>
                                                <th>Diagnosa</th>
                                                <th>Poli</th>
                                                <th>Biaya</th>
                                                <th>No BPJS</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $row_idx = 0;
                                            if (mysqli_num_rows($kunjungan) > 0):
                                                while ($row = mysqli_fetch_assoc($kunjungan)): ?>
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="id_kunjungan[]" value="<?= $row['id_kunjungan']; ?>" readonly>
                                                        <input type="date" name="tanggalkunjungan[]" class="form-control" value="<?= htmlspecialchars($row['tanggal_kunjungan']); ?>" required readonly>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="keluhankunjungan[]" class="form-control" value="<?= htmlspecialchars($row['keluhan_kunjungan']); ?>" required readonly>
                                                    </td>
                                                    <td><select name="polikunjungan[]" class="form-control" readonly disabled>
    <option value="RPU" <?= $row['poli_kunjungan'] == 'RPU' ? 'selected' : '' ?>>RPU</option>
    <option value="RPA" <?= $row['poli_kunjungan'] == 'RPA' ? 'selected' : '' ?>>RPA</option>
    <option value="Gigi" <?= $row['poli_kunjungan'] == 'Gigi' ? 'selected' : '' ?>>Gigi</option>
    <option value="KIA" <?= $row['poli_kunjungan'] == 'KIA' ? 'selected' : '' ?>>KIA</option>
    <option value="TB" <?= $row['poli_kunjungan'] == 'TB' ? 'selected' : '' ?>>TB</option>
</select></td>
                                                    <td>
                                                        <select name="biaya[]" class="form-control biaya-select-edit" disabled>
                                                            <option value="BPJS" <?= ($row['biaya_kunjungan'] == 'BPJS') ? 'selected' : ''; ?>>BPJS</option>
                                                            <option value="Umum" <?= ($row['biaya_kunjungan'] == 'Umum') ? 'selected' : ''; ?>>Umum</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="nobpjs[]" class="form-control nobpjs-kunjungan-edit" value="<?= htmlspecialchars($row['no_bpjs_kunjungan']); ?>" readonly>
                                                    </td>
                                                   <td>
                                                        <?php if (!empty($row['id_kunjungan'])): ?>
                                                            <button type="button" class="btn btn-danger btn-remove-row" onclick="hapusKunjungan('<?= $row['id_kunjungan']; ?>', this)" disabled>Hapus</button>
                                                        <?php else: ?>
                                                            <button type="button" class="btn btn-danger btn-remove-row" onclick="removeRow(this)" disabled>Hapus</button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php
                                                $row_idx++;
                                                endwhile;
                                            else: ?>
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="id_kunjungan[]" value="">
                                                        <input type="date" name="tanggalkunjungan[]" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="keluhankunjungan[]" class="form-control">
                                                    </td>
                                                    <td>
                                                       <select name="polikunjungan[]" class="form-control">
    <option value="RPU">RPU</option>
    <option value="RPA">RPA</option>
    <option value="Gigi">Gigi</option>
    <option value="KIA">KIA</option>
    <option value="TB">TB</option>
</select>
                                                    </td>
                                                    <td>
                                                        <select name="biaya[]" class="form-control biaya-select-edit">
                                                            <option value="BPJS" selected>BPJS</option>
                                                            <option value="Umum">Umum</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="nobpjs[]" class="form-control nobpjs-kunjungan-edit">
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-remove-row" onclick="removeRow(this)">Hapus</button>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                    <button type="button" class="btn btn-success" id="addRowBtn<?= $idpasien; ?>">Tambah Kunjungan</button>
                                </div>
                            </div>
                        </div>
                        <!-- Tab 4: Upload Rekam Medis -->
                        <div class="tab-pane fade" id="rm<?= $idpasien; ?>" role="tabpanel">
                            <?php
                            // Ambil data rekam medis
                            $rm = mysqli_query($koneksi, "SELECT * FROM rm WHERE id_pasien = '$idpasien' LIMIT 1");
                            $data_rm = mysqli_fetch_array($rm);
                            ?>
                            <div class="form-group">
                                <label>No Rekam Medis</label>
                                <input type="number" name="no_rm" class="form-control" value="<?= isset($data_rm['no_rm']) ? htmlspecialchars($data_rm['no_rm']) : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label class="small mb-1" for="berkas">File PDF Rekam Medis</label>
                                <?php if (!empty($data_rm['file_rm'])): ?>
                                    <div>
                                        <a href="../<?= htmlspecialchars($data_rm['file_rm']); ?>" target="_blank">Lihat File Saat Ini</a>
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="nama_file_pdf" id="nama_file_pdf" accept="application/pdf">
                                <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah file.</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- End Modal Edit Pasien -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi untuk modal edit dengan ID spesifik
    initBPJSBehavior('<?= $idpasien; ?>');
});

function initBPJSBehavior(modalId) {
    const nobpjsPasienInput = document.getElementById('nobpjs_pasien_' + modalId);
    const addRowBtn = document.getElementById('addRowBtn' + modalId);
    const dynamicField = document.getElementById('edit_dynamic_field' + modalId);
    
    let nobpjsPasienValue = nobpjsPasienInput?.value || '';
    
    // Simpan nilai No BPJS saat berubah
    if (nobpjsPasienInput) {
        nobpjsPasienInput.addEventListener('input', function() {
            nobpjsPasienValue = this.value;
        });
    }
    
    // Fungsi untuk menangani perubahan dropdown biaya
    function handleBiayaChange() {
        const biayaSelects = dynamicField.querySelectorAll('.biaya-select-edit');
        const nobpjsKunjunganInputs = dynamicField.querySelectorAll('.nobpjs-kunjungan-edit');
        
        biayaSelects.forEach((select, index) => {
            // Hapus event listener lama jika ada
            select.removeEventListener('change', handleBiayaChangeEvent);
            
            // Tambah event listener baru
            select.addEventListener('change', handleBiayaChangeEvent);
            
            function handleBiayaChangeEvent() {
                if (this.value === 'BPJS' && nobpjsPasienValue) {
                    nobpjsKunjunganInputs[index].value = nobpjsPasienValue;
                } else if (this.value === 'Umum') {
                    nobpjsKunjunganInputs[index].value = '';
                }
            }
            
            // Trigger perubahan awal untuk baris yang sudah ada
            if (select.value === 'BPJS' && nobpjsPasienValue && !nobpjsKunjunganInputs[index].value) {
                nobpjsKunjunganInputs[index].value = nobpjsPasienValue;
            }
        });
    }
    
    // Panggil fungsi saat modal dimuat
    handleBiayaChange();
    
    // Fungsi untuk menambah baris baru
    if (addRowBtn) {
        addRowBtn.addEventListener('click', function() {
            const tbody = dynamicField.querySelector('tbody');
            const newRow = document.createElement('tr');
            
            newRow.innerHTML = `
                <td>
                    <input type="hidden" name="id_kunjungan[]" value="">
                    <input type="date" name="tanggalkunjungan[]" class="form-control" required>
                </td>
                <td>
                    <input type="text" name="keluhankunjungan[]" class="form-control" required>
                </td>
                <td>
                    <select name="polikunjungan[]" class="form-control">
    <option value="RPU">RPU</option>
    <option value="RPA">RPA</option>
    <option value="Gigi">Gigi</option>
    <option value="KIA">KIA</option>
    <option value="TB">TB</option>
</select>
                </td>
                <td>
                    <select name="biaya[]" class="form-control biaya-select-edit">
                        <option value="BPJS" selected>BPJS</option>
                        <option value="Umum">Umum</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="nobpjs[]" class="form-control nobpjs-kunjungan-edit" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-remove-row" onclick="removeRow(this)">Hapus</button>
                </td>
            `;
            
            tbody.appendChild(newRow);
            
            // Panggil kembali fungsi untuk menangani dropdown pada baris baru
            handleBiayaChange();
        });
    }
}

// Fungsi global untuk menghapus baris
function removeRow(btn) {
    const row = btn.closest('tr');
    if (row) {
        row.remove();
    }
}

// Fungsi untuk hapus kunjungan dari database
function hapusKunjungan(idKunjungan, btn) {
    if (confirm('Apakah Anda yakin ingin menghapus kunjungan ini?')) {
        // Kirim request AJAX untuk hapus kunjungan
        fetch('../../proses/hapus_kunjungan.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id_kunjungan=' + idKunjungan
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                removeRow(btn);
                alert('Kunjungan berhasil dihapus');
            } else {
                alert('Gagal menghapus kunjungan: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus kunjungan');
        });
    }
}
</script>
                    </td>
                </tr>
                <!-- Modal Show RM -->
                <?php if (!empty($rekamid) && !empty($linkrm)): ?>
                <div class="modal fade" id="mm<?=$rekamid;?>">
                    <div class="modal-dialog modal-l">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Tampil Riwayat Kunjungan dan Rekam Medis</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" style="text-align: center;">
                                <div class="container">
                                   <embed type="application/pdf" src="../<?php echo $linkrm; ?>" width="100%" height="700"></embed>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <!-- End Modal Show RM -->

            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th>No</th>
                <th>No RM</th>
                <th>NIK </th>
                <th>Nama Pasien</th>
                <th>Tanggal Lahir</th>
                <th>Gender</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </tfoot>
    </table>
</div>

<!-- Pagination -->
<div class="card-footer clearfix">
    <ul class="pagination pagination-sm m-0 float-right">
        <?php if($page > 1): ?>
            <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>">&laquo;</a></li>
        <?php endif; ?>

        <?php for($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <?php if($page < $total_pages): ?>
            <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>">&raquo;</a></li>
        <?php endif; ?>
    </ul>
</div>

            <div>
           <!-- modal daftar -->
<div class="modal fade" id="modal-xl">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Pasien</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="text-align: center;">
                <!-- modal body -->
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <form action="../../proses/tambahalldata.php" method="post" class="f1"
                                enctype="multipart/form-data">
                                <div class="f1-steps">
                                    <div class="f1-progress">
                                        <div class="f1-progress-line" data-now-value="25"
                                            data-number-of-steps="4" style="width: 25%;"></div>
                                    </div>
                                    <div class="f1-step active">
                                        <div class="f1-step-icon"><i class="fa fa-info"></i></div>
                                        <p>Informasi</p>
                                    </div>
                                    <div class="f1-step">
                                        <div class="f1-step-icon"><i class="fa fa-user"></i></div>
                                        <p>Data Pasien</p>
                                    </div>
                                    <div class="f1-step">
                                        <div class="f1-step-icon"><i class="fa fa-location"></i></div>
                                        <p>Data Kunjungan</p>
                                    </div>
                                    <div class="f1-step">
                                        <div class="f1-step-icon"><i class="fa fa-upload"></i></div>
                                        <p>Upload Rekam Medis</p>
                                    </div>
                                </div>
                                <!-- step 1 -->
                                <fieldset>
                                    <h4>Informasi Pengisian</h4>
                                    <div class="form-group">
                                        <label>Nama Awal</label>
                                        <div class="f1-buttons">
                                            <button type="button"
                                                class="btn btn-primary btn-next">Selanjutnya <i
                                                    class="fa fa-arrow-right"></i></button>
                                        </div>
                                </fieldset>
                                <!-- step 2 -->
                                <fieldset>
                                    <h4>Isi Data Pasien</h4>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>NIK</label>
                                                <input type="number" name="nik"
                                                    placeholder="Nomor Induk Kependudukan"
                                                    class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Nama Pasien</label>
                                                <input type="text" name="namapasien"
                                                    placeholder="Nama Pasien" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Nama Kepala Keluarga Pasien</label>
                                                <input type="text" name="namakkpasien"
                                                    placeholder="Nama Kepala Keluarga Pasien"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Jenis Kelamin</label>
                                                <select class="form-control" name="jeniskelaminpasien">
                                                    <option value="Laki Laki">Laki Laki</option>
                                                    <option value="Perempuan">Perempuan</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Pekerjaan</label>
                                                <input type="text" name="pekerjaanpasien"
                                                    placeholder="Pekerjaan" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Tanggal Lahir</label>
                                                <input type="date" id="tanggallahirpasien"
                                                    name="tanggallahirpasien" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label>Agama</label>
                                                    <input type="text" name="agamapasien"
                                                        placeholder="Agama Pasien" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>No BPJS</label>
                                                <input type="text" name="nobpjs_pasien" id="nobpjs_pasien"
                                                    placeholder="No BPJS (Kosongi jika Umum)" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Alamat</label>
                                                <textarea name="alamatpasien"
                                                    placeholder="Alamat Pasien"
                                                    class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="f1-buttons">
                                        <button type="button" class="btn btn-warning btn-previous"><i
                                                class="fa fa-arrow-left"></i> Sebelumnya</button>
                                        <button type="button"
                                            class="btn btn-primary btn-next">Selanjutnya <i
                                                class="fa fa-arrow-right"></i></button>
                                    </div>
                                </fieldset>
                                <!-- step 3 -->
                                <fieldset>
                                    <h4>Data Kunjungan</h4>
                                    <div class="form-group">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="dynamic_field">
                                                <tr>
                                                    <td class="col-md-1">
                                                        <input type="date" name="tanggalkunjungan[]"
                                                            class="form-control nilai_list"
                                                            placeholder="Tanggal Kunjungan" required/>
                                                    </td>
                                                    <td class="col-md-3">
                                                        <input type="text" name="keluhankunjungan[]"
                                                            class="form-control nilai_list"
                                                            placeholder="Diagnosa" required/>
                                                    </td>
                                                    <td class="col-md-3">
                                                        <select name="polikunjungan[]" class="form-control nilai_list" required>
                                                            <option value="RPU">RPU</option>
                                                            <option value="RPA">RPA</option>
                                                            <option value="Gigi">Gigi</option>
                                                            <option value="KIA">KIA</option>
                                                            <option value="TB">TB</option>
                                                        </select>
                                                    </td>
                                                    <td class="col-md-2">
                                                        <select name="biaya[]" class="form-control biaya-select" required>
                                                            <option value="" disabled selected>Pilih Biaya</option>
                                                            <option value="BPJS">BPJS</option>
                                                            <option value="Umum">Umum</option>
                                                        </select>
                                                    </td>
                                                    <td class="col-md-3">
                                                        <input type="text" name="nobpjs[]"
                                                            class="form-control nobpjs-kunjungan"
                                                            placeholder="No BPJS (Kosongi jika Umum)" readonly/>
                                                    </td>
                                                    <td class="col-md-3"><button type="button"
                                                            name="add" id="add"
                                                            class="btn btn-success">Add More</button>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="f1-buttons">
                                        <button type="button" class="btn btn-warning btn-previous"><i
                                                class="fa fa-arrow-left"></i> Sebelumnya</button>
                                        <button type="button"
                                            class="btn btn-primary btn-next">Selanjutnya <i
                                                class="fa fa-arrow-right"></i></button>
                                    </div>
                                </fieldset>
                                <!-- step 4 -->
                                <fieldset>
                                    <h4>Upload Rekam Medis</h4>
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label>No Rekam Medis</label>
                                            <input type="number" name="norm"
                                                placeholder="Nomor Rekam Medis" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="small mb-1" for="berkas">File Pdf</label>
                                        <input type="file" name="nama_file_pdf" id="nama_file_pdf"
                                            accept="application/pdf">
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="halamanDisimpanCheckbox" name="halaman_disimpan">
                                            <label class="form-check-label" for="halamanDisimpanCheckbox">
                                                Apakah ada halaman yang mau disimpan? <br>
                                                <small class="text-muted">note : halaman yang disimpan akan tetap ada walau rekam medis dimusnahkan <p> jika tidak ada yang disimpan, bisa check lalu tulis - pada formnya</small>
                                            </label>
                                        </div>
                                        <div id="inputNoHalamanDisimpan" style="display:none; margin-top:10px;">
                                            <label for="no_halaman_disimpan">Masukan no halaman yang disimpan</label>
                                            <input type="text" class="form-control" name="no_halaman_disimpan" id="no_halaman_disimpan" placeholder="Contoh: 1, 2, 5-7">
                                        </div>
                                    </div>
                                    <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        var checkbox = document.getElementById('halamanDisimpanCheckbox');
                                        var inputBox = document.getElementById('inputNoHalamanDisimpan');
                                        var inputHalaman = document.getElementById('no_halaman_disimpan');
                                        checkbox.addEventListener('change', function() {
                                            if (checkbox.checked) {
                                                inputBox.style.display = 'block';
                                            } else {
                                                inputBox.style.display = 'none';
                                                inputHalaman.value = '';
                                            }
                                        });
                                    });
                                    </script>
                                    <div class="f1-buttons">
                                        <button type="button" class="btn btn-warning btn-previous"><i
                                                class="fa fa-arrow-left"></i> Sebelumnya</button>
                                        <button type="submit" class="btn btn-primary btn-submit"><i
                                                class="fa fa-save"></i> Submit</button>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- end modal body -->
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <a>Dengan menambah Data Pasien, Anda akan juga menambah Data Rekam Medis dan
                    Kunjungan</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- end modal daftar -->
<!-- ========================= -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variabel untuk menyimpan nilai No BPJS dari field 2
    let nobpjsPasienValue = '';
    
    // Ambil nilai No BPJS dari field 2 saat berubah
    const nobpjsPasienInput = document.getElementById('nobpjs_pasien');
    nobpjsPasienInput.addEventListener('input', function() {
        nobpjsPasienValue = this.value;
    });
    
    // Fungsi untuk menangani perubahan dropdown biaya
    function handleBiayaChange() {
        const biayaSelects = document.querySelectorAll('.biaya-select');
        const nobpjsKunjunganInputs = document.querySelectorAll('.nobpjs-kunjungan');
        
        biayaSelects.forEach((select, index) => {
            select.addEventListener('change', function() {
                if (this.value === 'BPJS' && nobpjsPasienValue) {
                    nobpjsKunjunganInputs[index].value = nobpjsPasienValue;
                } else if (this.value === 'Umum') {
                    nobpjsKunjunganInputs[index].value = '';
                }
            });
        });
    }
    
    // Panggil fungsi saat halaman dimuat untuk baris pertama
    handleBiayaChange();
    
    // Fungsi untuk menambah baris baru pada tabel kunjungan
    document.getElementById('add').addEventListener('click', function() {
        const dynamicField = document.getElementById('dynamic_field');
        const newRow = document.createElement('tr');
        
        newRow.innerHTML = `
            <td class="col-md-1">
                <input type="date" name="tanggalkunjungan[]" class="form-control nilai_list" placeholder="Tanggal Kunjungan" required/>
            </td>
            <td class="col-md-3">
                <input type="text" name="keluhankunjungan[]" class="form-control nilai_list" placeholder="Diagnosa" required/>
            </td>
            <td class="col-md-3">
                <select name="polikunjungan[]" class="form-control nilai_list" required>
                    <option value="RPU">RPU</option>
                    <option value="RPA">RPA</option>
                    <option value="Gigi">Gigi</option>
                    <option value="KIA">KIA</option>
                    <option value="TB">TB</option>
                </select>
            </td>
            <td class="col-md-2">
                <select name="biaya[]" class="form-control biaya-select" required>
                    <option value="" disabled selected>Pilih Biaya</option>
                    <option value="BPJS">BPJS</option>
                    <option value="Umum">Umum</option>
                </select>
            </td>
            <td class="col-md-3">
                <input type="text" name="nobpjs[]" class="form-control nobpjs-kunjungan" placeholder="No BPJS (Kosongi jika Umum)" />
            </td>
            <td class="col-md-3">
                <button type="button" name="remove" class="btn btn-danger btn_remove">Remove</button>
            </td>
        `;
        
        dynamicField.appendChild(newRow);
        
        // Tambahkan event listener untuk tombol remove
        newRow.querySelector('.btn_remove').addEventListener('click', function() {
            this.closest('tr').remove();
        });
        
        // Panggil kembali fungsi untuk menangani perubahan dropdown pada baris baru
        handleBiayaChange();
    });
    
    // Tambahkan event listener untuk tombol remove yang sudah ada
    document.querySelectorAll('.btn_remove').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('tr').remove();
        });
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fungsi untuk menangani perubahan dropdown biaya
    function handleBiayaChange() {
        const biayaSelects = document.querySelectorAll('.biaya-select');
        const nobpjsPasien = document.getElementById('nobpjs_pasien').value;
        
        biayaSelects.forEach((select, index) => {
            select.addEventListener('change', function() {
                const nobpjsInputs = document.querySelectorAll('.nobpjs-kunjungan');
                
                if (this.value === 'BPJS' && nobpjsPasien) {
                    nobpjsInputs[index].value = nobpjsPasien;
                } else if (this.value === 'Umum') {
                    nobpjsInputs[index].value = '';
                }
            });
        });
    }
    
    // Panggil fungsi saat halaman dimuat
    handleBiayaChange();
    
    // Fungsi untuk menambah baris baru pada tabel kunjungan
    document.getElementById('add').addEventListener('click', function() {
        const dynamicField = document.getElementById('dynamic_field');
        const newRow = document.createElement('tr');
        
        newRow.innerHTML = `
            <td class="col-md-1">
                <input type="date" name="tanggalkunjungan[]" class="form-control nilai_list" placeholder="Tanggal Kunjungan" required/>
            </td>
            <td class="col-md-3">
                <input type="text" name="keluhankunjungan[]" class="form-control nilai_list" placeholder="Diagnosa" required/>
            </td>
            <td class="col-md-3">
                <select name="polikunjungan[]" class="form-control nilai_list" required>
                    <option value="RPU">RPU</option>
                    <option value="RPA">RPA</option>
                    <option value="Gigi">Gigi</option>
                    <option value="KIA">KIA</option>
                    <option value="TB">TB</option>
                </select>
            </td>
            <td class="col-md-2">
                <select name="biaya[]" class="form-control biaya-select" required>
                    <option value="" disabled selected>Pilih Biaya</option>
                    <option value="BPJS">BPJS</option>
                    <option value="Umum">Umum</option>
                </select>
            </td>
            <td class="col-md-3">
                <input type="text" name="nobpjs[]" class="form-control nobpjs-kunjungan" placeholder="No BPJS (Kosongi jika Umum)" />
            </td>
            <td class="col-md-3">
                <button type="button" name="remove" class="btn btn-danger btn_remove">Remove</button>
            </td>
        `;
        
        dynamicField.appendChild(newRow);
        
        // Tambahkan event listener untuk tombol remove
        newRow.querySelector('.btn_remove').addEventListener('click', function() {
            this.closest('tr').remove();
        });
        
        // Panggil kembali fungsi untuk menangani perubahan dropdown pada baris baru
        handleBiayaChange();
    });
    
    // Tambahkan event listener untuk tombol remove yang sudah ada
    document.querySelectorAll('.btn_remove').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('tr').remove();
        });
    });
});
</script>
               
    </section>
</div>
<script>
function confirmDelete()
{
  if (confirm('Do you want to delete ?'))
  {
      return true;
  }
  else
  {
      return false;
  }
}

// Fungsi untuk hapus kunjungan AJAX
function hapusKunjungan(id_kunjungan, btn) {
    if (confirm('Yakin ingin menghapus kunjungan ini?')) {
        fetch(window.location.pathname + '?hapus_kunjungan_ajax=1&id_kunjungan=' + id_kunjungan, { method: 'GET' })
            .then(response => {
                // Cek apakah response berupa JSON atau plain text
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.indexOf('application/json') !== -1) {
                    return response.json();
                }
                return response.text();
            })
            .then(data => {
                // Jika response berupa HTML, kemungkinan session expired atau redirect ke login
                if (typeof data === 'string' && data.trim().startsWith('<!DOCTYPE html')) {
                    alert('Data Kunjungan Berhasil Di Hapus.');
                    window.location.reload();
                    return;
                }
                if (typeof data === 'string' && data.trim() === 'OK') {
                    removeRow(btn);
                } else if (typeof data === 'string') {
                    alert('Gagal menghapus data kunjungan!\n' + data);
                    console.error('Error detail:', data);
                } else if (typeof data === 'object' && data.status === 'OK') {
                    removeRow(btn);
                } else {
                    alert('Gagal menghapus data kunjungan!');
                    console.error('Error detail:', data);
                }
            })
            .catch((err) => {
                alert('Terjadi kesalahan saat menghapus data!');
                console.error(err);
            });
    }
}
</script>
<?php include 'template/footer.php'; ?>