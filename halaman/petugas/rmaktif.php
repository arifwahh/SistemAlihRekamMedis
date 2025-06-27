<?php include 'template/header.php'; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Rekam Medis Aktif</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="home.php">Beranda</a></li>
                        <li class="breadcrumb-item active">Rekam Medis Aktif</li>
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
                            <h3 class="card-title">Rekam Medis Aktif</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
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
                                    <?php include '../../proses/koneksi.php';
                  include '../../proses/tanggalindo.php';
                  $no = 1;
                  $data = mysqli_query($koneksi, "select DISTINCT a.id_pasien, a.nama_pasien, a.tanggal_lahir_pasien, a.jenis_kelamin_pasien, a.alamat_pasien, b.id_pasien from pasien a JOIN kunjungan b ON a.id_pasien = b.id_pasien WHERE b.tanggal_kunjungan > DATE_SUB(CURDATE(), INTERVAL 2 YEAR) ORDER BY b.tanggal_kunjungan DESC;");
                  while ($d = mysqli_fetch_array($data)) {
                  ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php
                          $idpasien = $d['id_pasien'];
                          $rekam = mysqli_query($koneksi, "select * from rm where id_pasien = '$idpasien'");
                          while ($tampilrekam = mysqli_fetch_array($rekam)) {
                          ?>
                                            <?php 
                                            $rekamid = $tampilrekam['no_rm'];
                                            $linkrm = $tampilrekam['file_rm'];
                                            echo $rekamid; ?>
                                            <?php } ?></td>
                                        <td><?php echo $d['nama_pasien']; ?></td>
                                        <td><?php echo tgl_indo(date($d['tanggal_lahir_pasien'])); ?></td>
                                        <td><?php echo $d['jenis_kelamin_pasien']; ?></td>
                                        <td><?php echo $d['alamat_pasien']; ?></td>
                                        <?php 
                                        $kunjungan = mysqli_query($koneksi, "select MAX(tanggal_kunjungan) as knj from kunjungan where id_pasien = '$idpasien'");
                                        while ($tampilkunjungan = mysqli_fetch_array($kunjungan)) { ?>
                                        <td> <a href="#" data-toggle="modal" data-target="#edit<?php echo $idpasien ?>"> <?php echo tgl_indo(date($tampilkunjungan['knj'])); }?></a></td>
                                        <td><span class="right badge badge-success">AKTIF</span></td>
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