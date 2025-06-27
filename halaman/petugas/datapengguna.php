<?php include 'template/header.php'; ?>
<!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Pengguna</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="home.php">Beranda</a></li>
              <li class="breadcrumb-item active">Data Pengguna</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
      <div class="container-fluid">
          <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Data Pengguna</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">No</th>
                      <th>Foto</th>
                      <th>Nama Petugas</th>
                      <th>Email</th>
                      <th>Jabatan</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
		include '../../proses/koneksi.php';
		$no = 1;
		$data = mysqli_query($koneksi,"select * from pengguna");
		while($d = mysqli_fetch_array($data)){
			?>
			        <tr>
                      <td><?php echo $no++; ?></td>
                      <td><?php echo $d['foto_pengguna']; ?></td>
                      <td><?php echo $d['nama_pengguna']; ?></td>
                      <td><?php echo $d['email_pengguna']; ?></td>
                      <td><?php echo $d['jabatan_pengguna']; ?></td>
                      <td><a class="far fa-folder" href="#">&nbsp;<a class="far fa-edit" href="#">&nbsp;<a class="fas fa-trash" href="#"></td>
                    </tr>
                    <?php 
		}
		?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                  <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                  <li class="page-item"><a class="page-link" href="#">1</a></li>
                  <li class="page-item"><a class="page-link" href="#">2</a></li>
                  <li class="page-item"><a class="page-link" href="#">3</a></li>
                  <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                </ul>
              </div>
            </div>
            <!-- /.card -->
          </div>
         </div>
      <div>
    </section>
</div>
<?php include 'template/footer.php'; ?>