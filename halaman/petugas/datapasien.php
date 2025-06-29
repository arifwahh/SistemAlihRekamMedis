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
                        <form action="../../proses/importpasien.php" method="post" enctype="multipart/form-data">
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
                                        <a href="../../proses/template_import_pasien.xlsx" class="btn btn-link">Download Template Excel</a>
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

            while ($d = mysqli_fetch_array($data)) { ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td>
                        <?php 
                        $idpasien = $d['id_pasien'];
                        $rekam = mysqli_query($koneksi, "SELECT * FROM rm WHERE id_pasien = '$idpasien'");
                        $rekamid = '';
                        $linkrm = '';
                        while ($tampilrekam = mysqli_fetch_array($rekam)) {
                            $rekamid = $tampilrekam['no_rm'];
                            $linkrm = $tampilrekam['file_rm'];
                            echo $rekamid; 
                        }
                        ?>
                    </td>
                    <td><?php echo $d['nik_pasien']; ?></td>
                    <td><?php echo $d['nama_pasien']; ?></td>
                    <td><?php echo tgl_indo(date($d['tanggal_lahir_pasien'])); ?></td>
                    <td><?php echo $d['jenis_kelamin_pasien']; ?></td>
                    <td><?php echo $d['alamat_pasien']; ?></td>
                    <td>
                        <a class="far fa-folder" data-toggle="modal" data-target="#mm<?=$rekamid;?>"></a>&nbsp;
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
                                                <th>Klinik</th>
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
                                                        <input type="hidden" name="id_kunjungan[]" value="<?= $row['id_kunjungan']; ?>">
                                                        <input type="date" name="tanggalkunjungan[]" class="form-control" value="<?= htmlspecialchars($row['tanggal_kunjungan']); ?>" required>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="keluhankunjungan[]" class="form-control" value="<?= htmlspecialchars($row['keluhan_kunjungan']); ?>" required>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="polikunjungan[]" class="form-control" value="<?= htmlspecialchars($row['poli_kunjungan']); ?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="klinikkunjungan[]" class="form-control" value="<?= htmlspecialchars($row['klinik_kunjungan']); ?>">
                                                    </td>
                                                    <td>
                                                        <select name="biaya[]" class="form-control">
                                                            <option value="BPJS" <?= ($row['biaya_kunjungan'] == 'BPJS') ? 'selected' : ''; ?>>BPJS</option>
                                                            <option value="Umum" <?= ($row['biaya_kunjungan'] == 'Umum') ? 'selected' : ''; ?>>Umum</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="nobpjs[]" class="form-control" value="<?= htmlspecialchars($row['no_bpjs_kunjungan']); ?>">
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($row['id_kunjungan'])): ?>
                                                            <button type="button" class="btn btn-danger btn-remove-row" onclick="hapusKunjungan('<?= $row['id_kunjungan']; ?>', this)">Hapus</button>
                                                        <?php else: ?>
                                                            <button type="button" class="btn btn-danger btn-remove-row" onclick="removeRow(this)">Hapus</button>
                                                        <?php endif; ?>
                                                        <?php
                                                        // Proses hapus kunjungan AJAX
                                                        if (isset($_GET['hapus_kunjungan_ajax']) && isset($_GET['id_kunjungan'])) {
                                                            include_once '../../proses/koneksi.php';
                                                            $id_kunjungan = intval($_GET['id_kunjungan']);
                                                            $query = mysqli_query($koneksi, "SELECT id_pasien FROM kunjungan WHERE id_kunjungan = '$id_kunjungan'");
                                                            if (!$query || mysqli_num_rows($query) == 0) {
                                                                echo 'ERROR: Kunjungan tidak ditemukan';
                                                                exit;
                                                            }
                                                            $data = mysqli_fetch_assoc($query);
                                                            $id_pasien = $data['id_pasien'];
                                                            mysqli_begin_transaction($koneksi);
                                                            try {
                                                                $check_rm_reference = mysqli_query($koneksi, "SELECT rm_id FROM rm WHERE id_kunjungan_terakhir = '$id_kunjungan'");
                                                                $hapus = mysqli_query($koneksi, "DELETE FROM kunjungan WHERE id_kunjungan = '$id_kunjungan'");
                                                                if (!$hapus) throw new Exception("Gagal menghapus kunjungan");
                                                                if (mysqli_num_rows($check_rm_reference) > 0) {
                                                                    $query_kunjungan_terakhir = mysqli_query($koneksi, "SELECT id_kunjungan FROM kunjungan WHERE id_pasien = '$id_pasien' ORDER BY tanggal_kunjungan DESC, id_kunjungan DESC LIMIT 1");
                                                                    $id_kunjungan_terakhir = null;
                                                                    if (mysqli_num_rows($query_kunjungan_terakhir) > 0) {
                                                                        $data_terakhir = mysqli_fetch_assoc($query_kunjungan_terakhir);
                                                                        $id_kunjungan_terakhir = $data_terakhir['id_kunjungan'];
                                                                    }
                                                                    $update_rm = mysqli_query($koneksi, "UPDATE rm SET id_kunjungan_terakhir = " . ($id_kunjungan_terakhir ? "'$id_kunjungan_terakhir'" : "NULL") . ", tanggal_status = NOW() WHERE id_pasien = '$id_pasien'");
                                                                    if (!$update_rm) throw new Exception("Gagal memperbarui rekam medis");
                                                                }
                                                                mysqli_commit($koneksi);
                                                                echo 'OK';
                                                            } catch (Exception $e) {
                                                                mysqli_rollback($koneksi);
                                                                echo 'ERROR: ' . $e->getMessage();
                                                            }
                                                            exit;
                                                        }
                                                        ?>
                                                        <script>
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
                                                        <input type="text" name="polikunjungan[]" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="klinikkunjungan[]" class="form-control">
                                                    </td>
                                                    <td>
                                                        <select name="biaya[]" class="form-control">
                                                            <option value="BPJS" selected>BPJS</option>
                                                            <option value="Umum">Umum</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="nobpjs[]" class="form-control">
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
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    document.getElementById('addRowBtn<?= $idpasien; ?>').addEventListener('click', function() {
                                        var table = document.getElementById('edit_dynamic_field<?= $idpasien; ?>').getElementsByTagName('tbody')[0];
                                        var newRow = table.insertRow();
                                        newRow.innerHTML = `
                                            <td>
                                                <input type="hidden" name="id_kunjungan[]" value="">
                                                <input type="date" name="tanggalkunjungan[]" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="keluhankunjungan[]" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="polikunjungan[]" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="klinikkunjungan[]" class="form-control">
                                            </td>
                                            <td>
                                                <select name="biaya[]" class="form-control">
                                                    <option value="BPJS" selected>BPJS</option>
                                                    <option value="Umum">Umum</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="nobpjs[]" class="form-control">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-remove-row" onclick="removeRow(this)">Hapus</button>
                                            </td>
                                        `;
                                    });
                                });
                                function removeRow(btn) {
                                    var row = btn.closest('tr');
                                    row.parentNode.removeChild(row);
                                }
                            </script>
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

                    </td>
                </tr>
                <!-- Modal Show RM -->
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
                                                                            placeholder="Tanggal Kunjungan" />
                                                                    </td>
                                                                    <td class="col-md-3">
                                                                        <input type="text" name="keluhankunjungan[]"
                                                                            class="form-control nilai_list"
                                                                            placeholder="Diagnosa" />
                                                                    </td>
                                                                    <td class="col-md-1">
                                                                        <input type="text" name="polikunjungan[]"
                                                                            class="form-control nilai_list"
                                                                            placeholder="Poli" />
                                                                    </td>
                                                                    <td class="col-md-2">
                                                                        <input type="text" name="klinikkunjungan[]"
                                                                            class="form-control nilai_list"
                                                                            placeholder="Klinik" />
                                                                    </td>
                                                                    <td class="col-md-2">
                                                                        <select name="biaya[]" class="form-control">
                                                                            Biaya
                                                                            <option value="BPJS" selected>BPJS</option>
                                                                            <option value="Umum">Umum</option>
                                                                        </select>
                                                                    </td>
                                                                    <td class="col-md-3">
                                                                        <input type="text" name="nobpjs[]"
                                                                            class="form-control nilai_list"
                                                                            placeholder="No BPJS (Kosongi jika Umum)" />
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
                                                                // inputHalaman.required = true; // Tidak required
                                                            } else {
                                                                inputBox.style.display = 'none';
                                                                inputHalaman.value = '';
                                                                // inputHalaman.required = false; // Tidak required
                                                            }
                                                        });
                                                        // Pastikan input tidak required saat load
                                                        // inputHalaman.required = false;
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
</script>
<?php include 'template/footer.php'; ?>