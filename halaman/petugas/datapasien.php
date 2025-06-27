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
                    <p><button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-xl">Tambah
                            Pasien</button>
                            <a href="../../proses/eksportxls.php" target="_blank" class="btn btn-success"><i class="far fa-print"></i> &nbsp Export Excel</a></p>
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
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $offset = ($page - 1) * $limit;

            // Mengambil total jumlah data pasien untuk pagination
            $result = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pasien");
            $total_rows = mysqli_fetch_assoc($result)['total'];
            $total_pages = ceil($total_rows / $limit);

            // Ambil data pasien dengan limit dan offset
            $data = mysqli_query($koneksi, "SELECT * FROM pasien LIMIT $limit OFFSET $offset");
            $no = $offset + 1;

            while ($d = mysqli_fetch_array($data)) { ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td>
                        <?php 
                        $idpasien = $d['id_pasien'];
                        $rekam = mysqli_query($koneksi, "SELECT * FROM rm WHERE id_pasien = '$idpasien'");
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

                <!-- Modal Edit -->
                <div class="modal fade" id="edit<?=$idpasien;?>" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">×</button>
                                <h4 class="modal-title">Detail Barang</h4>
                            </div>
                            <div class="modal-body">
                                <div class="fetched-data"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Keluar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Modal Edit -->
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
                                                                            placeholder="Keluhan" />
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
                                                                placeholder="Nomor Rekam Medis" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="berkas">File Pdf</label>
                                                        <input type="file" name="nama_file_pdf" id="nama_file_pdf"
                                                            accept="application/pdf">
                                                    </div>
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