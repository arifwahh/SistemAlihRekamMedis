<?php include 'template/header.php'; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Rekam Medis Musnah</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="home.php">Beranda</a></li>
                        <li class="breadcrumb-item active">Rekam Medis Musnah</li>
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
                            <h3 class="card-title">Rekam Medis Musnah</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <!-- Search Form -->
                            <form method="GET" class="form-inline mb-3">
                                <div class="form-group mr-2">
                                    <select name="kategori" class="form-control">
                                        <option value="">-- Pilih Kategori --</option>
                                        <option value="no_rm" <?php if(isset($_GET['kategori']) && $_GET['kategori']=='no_rm') echo 'selected'; ?>>No RM</option>
                                        <option value="nama_pasien" <?php if(isset($_GET['kategori']) && $_GET['kategori']=='nama_pasien') echo 'selected'; ?>>Nama Pasien</option>
                                        <option value="tanggal_lahir_pasien" <?php if(isset($_GET['kategori']) && $_GET['kategori']=='tanggal_lahir_pasien') echo 'selected'; ?>>Tanggal Lahir</option>
                                        <option value="jenis_kelamin_pasien" <?php if(isset($_GET['kategori']) && $_GET['kategori']=='jenis_kelamin_pasien') echo 'selected'; ?>>Gender</option>
                                        <option value="alamat_pasien" <?php if(isset($_GET['kategori']) && $_GET['kategori']=='alamat_pasien') echo 'selected'; ?>>Alamat</option>
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <input type="text" name="keyword" class="form-control" placeholder="Kata kunci" value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
                                </div>
                                <button type="submit" class="btn btn-primary">Cari</button>
                                <a href="rmaktif.php" class="btn btn-secondary ml-2">Reset</a>
                            </form>

                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No RM</th>
                                        <th>Nama Pasien</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Gender</th>
                                        <th>Alamat</th>
                                        <th>Tgl Kunjungan Terakhir</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    include '../../proses/koneksi.php';
                                    include '../../proses/tanggalindo.php';
                                    $no = 1;

                                    // Handle search
                                    $where = "LEFT JOIN rm ON a.id_pasien = rm.id_pasien WHERE rm.status = 'MUSNAH'";
                                    $join = "JOIN kunjungan b ON a.id_pasien = b.id_pasien";
                                    $kategori_map = [
                                        'no_rm' => "rm.no_rm",
                                        'nama_pasien' => "a.nama_pasien",
                                        'tanggal_lahir_pasien' => "a.tanggal_lahir_pasien",
                                        'jenis_kelamin_pasien' => "a.jenis_kelamin_pasien",
                                        'alamat_pasien' => "a.alamat_pasien"
                                    ];

                                    if (!empty($_GET['kategori']) && !empty($_GET['keyword']) && isset($kategori_map[$_GET['kategori']])) {
                                        $kategori = mysqli_real_escape_string($koneksi, $_GET['kategori']);
                                        $keyword = mysqli_real_escape_string($koneksi, $_GET['keyword']);
                                        if($kategori == 'no_rm') {
                                            $join .= " LEFT JOIN rm ON a.id_pasien = rm.id_pasien";
                                            $where .= " AND rm.no_rm LIKE '%$keyword%'";
                                        } else {
                                            $where .= " AND {$kategori_map[$kategori]} LIKE '%$keyword%'";
                                        }
                                    }

                                    $sql = "SELECT DISTINCT a.id_pasien, a.nama_pasien, a.tanggal_lahir_pasien, a.jenis_kelamin_pasien, a.alamat_pasien, b.id_pasien 
                                            FROM pasien a $join $where ORDER BY b.tanggal_kunjungan DESC";
                                    $data = mysqli_query($koneksi, $sql);

                                    while ($d = mysqli_fetch_array($data)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td>
                                            <?php
                                            $idpasien = $d['id_pasien'];
                                            $rekam = mysqli_query($koneksi, "select * from rm where id_pasien = '$idpasien'");
                                            while ($tampilrekam = mysqli_fetch_array($rekam)) {
                                                $rekamid = $tampilrekam['no_rm'];
                                                $linkrm = $tampilrekam['file_rm'];
                                                echo $rekamid;
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo $d['nama_pasien']; ?></td>
                                        <td><?php echo tgl_indo(date($d['tanggal_lahir_pasien'])); ?></td>
                                        <td><?php echo $d['jenis_kelamin_pasien']; ?></td>
                                        <td><?php echo $d['alamat_pasien']; ?></td>
                                        <?php 
                                        $kunjungan = mysqli_query($koneksi, "select MAX(tanggal_kunjungan) as knj from kunjungan where id_pasien = '$idpasien'");
                                        while ($tampilkunjungan = mysqli_fetch_array($kunjungan)) { ?>
                                        <td> <a href="#" data-toggle="modal" data-target="#edit<?php echo $idpasien ?>"> <?php echo tgl_indo(date($tampilkunjungan['knj'])); }?></a></td>
                                        <td>
                                            <span class="right badge badge-danger">MUSNAH</span>
                                            <?php
                                            // Ambil data halaman dari tabel rm kolom halaman_rm
                                            $halaman_query = mysqli_query($koneksi, "SELECT halaman_rm FROM rm WHERE no_rm = '$rekamid' LIMIT 1");
                                            $halaman_data = mysqli_fetch_assoc($halaman_query);
                                            $halaman_str = isset($halaman_data['halaman_rm']) ? trim($halaman_data['halaman_rm']) : '1';

                                            // Tampilkan icon hanya jika $linkrm tidak kosong DAN $halaman_str bukan "-"
                                            if (!empty($linkrm) && $halaman_str !== "-") { ?>
                                                <a href="#" data-toggle="modal" data-target="#fileModal<?php echo $idpasien; ?>" title="Lihat File RM">
                                                    <i class="fa fa-folder-open text-primary ml-2"></i>
                                                </a>
                                                <!-- Modal -->
<div class="modal fade" id="fileModal<?php echo $idpasien; ?>" tabindex="-1" role="dialog" aria-labelledby="fileModalLabel<?php echo $idpasien; ?>" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="fileModalLabel<?php echo $idpasien; ?>">File Rekam Medis</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="pdf-viewer-<?php echo $idpasien; ?>" style="width:100%; height:600px; border:1px solid #ccc; overflow:auto;"></div>
        <p>Menampilkan file rekam medis.</p>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
        <script>
        (function() {
            var url = "../<?php echo $linkrm ? htmlspecialchars($linkrm, ENT_QUOTES) : ''; ?>";
            var containerId = "pdf-viewer-<?php echo $idpasien; ?>";
            var container = document.getElementById(containerId);
            container.innerHTML = "";

            pdfjsLib.GlobalWorkerOptions.workerSrc = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js";
            pdfjsLib.getDocument(url).promise.then(function(pdf) {
                // Render semua halaman PDF
                for (var pageNumber = 1; pageNumber <= pdf.numPages; pageNumber++) {
                    pdf.getPage(pageNumber).then(function(page) {
                        var viewport = page.getViewport({scale: 1.5});
                        var canvas = document.createElement('canvas');
                        var context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;
                        canvas.style.marginBottom = "20px";
                        container.appendChild(canvas);

                        var renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };
                        page.render(renderContext);
                    });
                }
            }).catch(function(error) {
                container.innerHTML = "<div style='color:red;'>File PDF tidak ditemukan atau rusak.</div>";
            });
        })();
        </script>
      </div>
    </div>
  </div>
</div>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php
                                        include '../../proses/showkunjungan.php';
                                    ?>  
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>No RM</th>
                                        <th>Nama Pasien</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Gender</th>
                                        <th>Alamat</th>
                                        <th>Tgl Kunjungan Terakhir</th>
                                        <th>Status</th>
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