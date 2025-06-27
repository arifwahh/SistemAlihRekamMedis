<?php include 'template/header.php'; ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Berita Acara</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="home.php">Beranda</a></li>
                        <li class="breadcrumb-item active">Berita Acara</li>
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
            <!-- Tombol Tambah Berita Acara -->
            <div class="row mb-2">
                <div class="col-md-12">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahBA">
                        Tambah Berita Acara
                    </button>
                </div>
            </div>

            <!-- Modal Tambah Berita Acara -->
            <div class="modal fade" id="modalTambahBA" tabindex="-1" role="dialog" aria-labelledby="modalTambahBALabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <form action="../../proses/beritaacara_tambah.php" method="POST">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modalTambahBALabel">Tambah Berita Acara</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="id_ba">ID Berita Acara</label>
                            <input type="text" class="form-control" id="id_ba" name="id_ba" required maxlength="150">
                        </div>
                        <div class="form-group">
                            <label for="tanggal_ba">Tanggal Pelaksanaan</label>
                            <input type="datetime-local" class="form-control" id="tanggal_ba" name="tanggal_ba" required>
                        </div>
                        <div class="form-group">
                            <label for="judul_ba">Judul</label>
                            <input type="text" class="form-control" id="judul_ba" name="judul_ba" required maxlength="500">
                        </div>
                        <div class="form-group">
                            <label for="pj_apoteker_ba">Penanggung Jawab</label>
                            <input type="text" class="form-control" id="pj_apoteker_ba" name="pj_apoteker_ba" required maxlength="50">
                        </div>
                        <div class="form-group">
                            <label for="pj_nip_ba">NIP Penanggung Jawab</label>
                            <input type="text" class="form-control" id="pj_nip_ba" name="pj_nip_ba" required maxlength="30">
                        </div>
                        <div class="form-group">
                            <label for="saksi_ba">Saksi</label>
                            <input type="text" class="form-control" id="saksi_ba" name="saksi_ba" required maxlength="50">
                        </div>
                        <div class="form-group">
                            <label for="saksi_nip_ba">NIP Saksi</label>
                            <input type="text" class="form-control" id="saksi_nip_ba" name="saksi_nip_ba" required maxlength="30">
                        </div>
                        <div class="form-group">
                            <label for="saksi_jabatan_ba">Jabatan Saksi</label>
                            <input type="text" class="form-control" id="saksi_jabatan_ba" name="saksi_jabatan_ba" required maxlength="70">
                        </div>
                        <div class="form-group">
                            <label for="tipe_ba">Tipe Berita Acara</label>
                            <select class="form-control" id="tipe_ba" name="tipe_ba" required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="PEMUSNAHAN">PEMUSNAHAN</option>
                                <option value="RETENSI">RETENSI</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="periodeawal_ba">Periode Awal</label>
                            <input type="date" class="form-control" id="periodeawal_ba" name="periodeawal_ba">
                        </div>
                        <div class="form-group">
                            <label for="periodeakhir_ba">Periode Akhir</label>
                            <input type="date" class="form-control" id="periodeakhir_ba" name="periodeakhir_ba">
                        </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                      <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>

            <!-- Tabel Berita Acara -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Berita Acara</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <!-- Search Form -->
                            <form method="GET" class="form-inline mb-3">
                                <div class="form-group mr-2">
                                    <select name="kategori" class="form-control">
                                        <option value="">-- Pilih Kategori --</option>
                                        <option value="tanggal_ba" <?php if(isset($_GET['kategori']) && $_GET['kategori']=='tanggal_ba') echo 'selected'; ?>>Tanggal Pelaksanaan</option>
                                        <option value="judul_ba" <?php if(isset($_GET['kategori']) && $_GET['kategori']=='judul_ba') echo 'selected'; ?>>Judul</option>
                                        <option value="pj_apoteker_ba" <?php if(isset($_GET['kategori']) && $_GET['kategori']=='pj_apoteker_ba') echo 'selected'; ?>>Penanggung Jawab</option>
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <input type="text" name="keyword" class="form-control" placeholder="Kata kunci" value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
                                </div>
                                <button type="submit" class="btn btn-primary">Cari</button>
                                <a href="beritaacara.php" class="btn btn-secondary ml-2">Reset</a>
                            </form>

                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Pelaksanaan</th>
                                        <th>Judul</th>
                                        <th>Penananggung Jawab</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    include '../../proses/koneksi.php';
                                    include '../../proses/tanggalindo.php';
                                    $no = 1;

                                    // Handle search
                                    $where = "WHERE 1=1";
                                    $kategori_map = [
                                        'tanggal_ba' => "tanggal_ba",
                                        'judul_ba' => "judul_ba",
                                        'pj_apoteker_ba' => "pj_apoteker_ba"
                                    ];

                                    if (!empty($_GET['kategori']) && !empty($_GET['keyword']) && isset($kategori_map[$_GET['kategori']])) {
                                        $kategori = mysqli_real_escape_string($koneksi, $_GET['kategori']);
                                        $keyword = mysqli_real_escape_string($koneksi, $_GET['keyword']);
                                        $where .= " AND {$kategori_map[$kategori]} LIKE '%$keyword%'";
                                    }

                                    $sql = "SELECT * FROM berita_acara $where ORDER BY tanggal_ba DESC";
                                    $data = mysqli_query($koneksi, $sql);

                                    while ($d = mysqli_fetch_array($data)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo tgl_indo(date($d['tanggal_ba'])); ?></td>
                                        <td><?php echo htmlspecialchars($d['judul_ba']); ?></td>
                                        <td><?php echo htmlspecialchars($d['pj_apoteker_ba']); ?></td>
                                        <td>
                                            <a href="../../proses/beritaacara_cetak.php?id_ba=<?php echo $d['id_ba']; ?>" target="_blank" class="btn btn-success btn-sm ">Cetak PDF</a>
                                            <a href="beritaacara_edit.php?id_ba=<?php echo $d['id_ba']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="beritaacara_hapus.php?id_ba=<?php echo $d['id_ba']; ?>" class="btn btn-danger btn-sm" onclick="return confirmDelete();">Hapus</a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Pelaksanaan</th>
                                        <th>Judul</th>
                                        <th>Penananggung Jawab</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
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