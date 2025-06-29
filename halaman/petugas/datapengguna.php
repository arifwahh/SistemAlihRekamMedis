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
              <!-- Button trigger modal -->
              <button type="button" class="btn btn-primary m-3" data-toggle="modal" data-target="#tambahPenggunaModal">
                Tambah Pengguna
              </button>

              <!-- Modal -->
              <div class="modal fade" id="tambahPenggunaModal" tabindex="-1" role="dialog" aria-labelledby="tambahPenggunaModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <form action="../../proses/tambah_pengguna_proses.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="tambahPenggunaModalLabel">Tambah Pengguna</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                          <div class="form-group">
                            <label for="email_pengguna">Email</label>
                            <input type="email" class="form-control" id="email_pengguna" name="email_pengguna" required>
                          </div>
                          <div class="form-group">
                            <label for="password_pengguna">Password</label>
                            <input type="password" class="form-control" id="password_pengguna" name="password_pengguna" required>
                          </div>
                          <div class="form-group">
                            <label for="nama_pengguna">Nama Petugas</label>
                            <input type="text" class="form-control" id="nama_pengguna" name="nama_pengguna" required>
                          </div>
                            <div class="form-group">
                            <label for="jabatan_pengguna">Jabatan</label>
                            <select class="form-control" id="jabatan_pengguna" name="jabatan_pengguna" required>
                              <option value="">-- Pilih Jabatan --</option>
                              <option value="petugas">Petugas</option>
                              <option value="kepala">Kepala</option>
                            </select>
                            </div>
                            <div class="form-group">
                            <label for="foto_pengguna">Foto</label>
                            <input type="file" class="form-control-file" id="foto_pengguna" name="foto_pengguna" accept="image/*" onchange="previewAndCropImage(event)">
                            <div id="preview-container" style="margin-top:10px; display:none;">
                              <canvas id="cropped-preview" style="border-radius:50%; width:120px; height:120px; border:1px solid #ccc;"></canvas>
                            </div>
                            <input type="hidden" name="cropped_image" id="cropped_image">
                            </div>
                            <script>
                            function previewAndCropImage(event) {
                            const file = event.target.files[0];
                            if (!file) return;
                            const reader = new FileReader();
                            reader.onload = function(e) {
                              const img = new Image();
                              img.onload = function() {
                              // Crop to center square
                              const size = Math.min(img.width, img.height);
                              const sx = (img.width - size) / 2;
                              const sy = (img.height - size) / 2;
                              const canvas = document.getElementById('cropped-preview');
                              const ctx = canvas.getContext('2d');
                              canvas.width = 120;
                              canvas.height = 120;
                              ctx.clearRect(0, 0, canvas.width, canvas.height);
                              // Draw circle mask
                              ctx.save();
                              ctx.beginPath();
                              ctx.arc(60, 60, 60, 0, Math.PI * 2, true);
                              ctx.closePath();
                              ctx.clip();
                              ctx.drawImage(img, sx, sy, size, size, 0, 0, 120, 120);
                              ctx.restore();
                              document.getElementById('preview-container').style.display = 'block';
                              // Save cropped image as base64 to hidden input
                              document.getElementById('cropped_image').value = canvas.toDataURL('image/png');
                              };
                              img.src = e.target.result;
                            };
                            reader.readAsDataURL(file);
                            }
                            </script>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
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
                        <td>
                        <?php if (!empty($d['foto_pengguna'])): ?>
                          <img src="../../assets/fotopengguna/<?php echo htmlspecialchars($d['foto_pengguna']); ?>" alt="Foto Pengguna" style="width:60px; height:60px; object-fit:cover; border-radius:50%;">
                        <?php else: ?>
                          <span class="text-muted">Tidak ada foto</span>
                        <?php endif; ?>
                        </td>
                      <td><?php echo $d['nama_pengguna']; ?></td>
                      <td><?php echo $d['email_pengguna']; ?></td>
                      <td><?php echo $d['jabatan_pengguna']; ?></td>
                        <td>
                        <!-- Tombol Edit -->
                        <a class="far fa-edit text-primary" href="#" data-toggle="modal" data-target="#editPenggunaModal<?php echo $d['id_pengguna']; ?>"></a>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="editPenggunaModal<?php echo $d['id_pengguna']; ?>" tabindex="-1" role="dialog" aria-labelledby="editPenggunaModalLabel<?php echo $d['id_pengguna']; ?>" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                          <form action="../../proses/edit_pengguna_proses.php" method="POST" enctype="multipart/form-data">
                            <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="editPenggunaModalLabel<?php echo $d['id_pengguna']; ?>">Edit Pengguna</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <input type="hidden" name="id_pengguna" value="<?php echo $d['id_pengguna']; ?>">
                              <div class="form-group">
                                <label for="email_pengguna_edit<?php echo $d['id_pengguna']; ?>">Email</label>
                                <input type="email" class="form-control" id="email_pengguna_edit<?php echo $d['id_pengguna']; ?>" name="email_pengguna" value="<?php echo htmlspecialchars($d['email_pengguna']); ?>" required>
                              </div>
                              <div class="form-group">
                                <label for="password_pengguna_edit<?php echo $d['id_pengguna']; ?>">Password <small class="text-muted">(Kosongkan jika tidak diubah)</small></label>
                                <input type="password" class="form-control" id="password_pengguna_edit<?php echo $d['id_pengguna']; ?>" name="password_pengguna">
                              </div>
                              <div class="form-group">
                                <label for="nama_pengguna_edit<?php echo $d['id_pengguna']; ?>">Nama Petugas</label>
                                <input type="text" class="form-control" id="nama_pengguna_edit<?php echo $d['id_pengguna']; ?>" name="nama_pengguna" value="<?php echo htmlspecialchars($d['nama_pengguna']); ?>" required>
                              </div>
                              <div class="form-group">
                                <label for="jabatan_pengguna_edit<?php echo $d['id_pengguna']; ?>">Jabatan</label>
                                <select class="form-control" id="jabatan_pengguna_edit<?php echo $d['id_pengguna']; ?>" name="jabatan_pengguna" required>
                                <option value="">-- Pilih Jabatan --</option>
                                <option value="petugas" <?php if($d['jabatan_pengguna']=='petugas') echo 'selected'; ?>>Petugas</option>
                                <option value="kepala" <?php if($d['jabatan_pengguna']=='kepala') echo 'selected'; ?>>Kepala</option>
                                </select>
                              </div>
                              <div class="form-group">
                                <label for="foto_pengguna_edit<?php echo $d['id_pengguna']; ?>">Foto</label>
                                <input type="file" class="form-control-file" id="foto_pengguna_edit<?php echo $d['id_pengguna']; ?>" name="foto_pengguna" accept="image/*" onchange="previewAndCropImageEdit(event, <?php echo $d['id_pengguna']; ?>)">
                                <div id="preview-container-edit<?php echo $d['id_pengguna']; ?>" style="margin-top:10px; <?php echo !empty($d['foto_pengguna']) ? 'display:block;' : 'display:none;'; ?>">
                                <canvas id="cropped-preview-edit<?php echo $d['id_pengguna']; ?>" style="border-radius:50%; width:120px; height:120px; border:1px solid #ccc;"></canvas>
                                </div>
                                <input type="hidden" name="cropped_image" id="cropped_image_edit<?php echo $d['id_pengguna']; ?>">
                                <?php if (!empty($d['foto_pengguna'])): ?>
                                <script>
                                  // Tampilkan foto lama di canvas saat modal dibuka
                                  document.addEventListener('DOMContentLoaded', function() {
                                  var img = new Image();
                                  img.onload = function() {
                                    var canvas = document.getElementById('cropped-preview-edit<?php echo $d['id_pengguna']; ?>');
                                    var ctx = canvas.getContext('2d');
                                    canvas.width = 120;
                                    canvas.height = 120;
                                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                                    ctx.save();
                                    ctx.beginPath();
                                    ctx.arc(60, 60, 60, 0, Math.PI * 2, true);
                                    ctx.closePath();
                                    ctx.clip();
                                    ctx.drawImage(img, 0, 0, 120, 120);
                                    ctx.restore();
                                  };
                                  img.src = "../../assets/fotopengguna/<?php echo htmlspecialchars($d['foto_pengguna']); ?>";
                                  });
                                </script>
                                <?php endif; ?>
                              </div>
                              <script>
                              function previewAndCropImageEdit(event, id) {
                                const file = event.target.files[0];
                                if (!file) return;
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                const img = new Image();
                                img.onload = function() {
                                  const size = Math.min(img.width, img.height);
                                  const sx = (img.width - size) / 2;
                                  const sy = (img.height - size) / 2;
                                  const canvas = document.getElementById('cropped-preview-edit' + id);
                                  const ctx = canvas.getContext('2d');
                                  canvas.width = 120;
                                  canvas.height = 120;
                                  ctx.clearRect(0, 0, canvas.width, canvas.height);
                                  ctx.save();
                                  ctx.beginPath();
                                  ctx.arc(60, 60, 60, 0, Math.PI * 2, true);
                                  ctx.closePath();
                                  ctx.clip();
                                  ctx.drawImage(img, sx, sy, size, size, 0, 0, 120, 120);
                                  ctx.restore();
                                  document.getElementById('preview-container-edit' + id).style.display = 'block';
                                  document.getElementById('cropped_image_edit' + id).value = canvas.toDataURL('image/png');
                                };
                                img.src = e.target.result;
                                };
                                reader.readAsDataURL(file);
                              }
                              </script>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                              <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                            </div>
                          </form>
                          </div>
                        </div>
                        <a class="fas fa-trash text-danger" href="../../proses/hapuspengguna.php?hapus=<?php echo $d['id_pengguna']; ?>" onclick="return confirm('Yakin ingin menghapus pengguna ini?')"></a>
                        </td>
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