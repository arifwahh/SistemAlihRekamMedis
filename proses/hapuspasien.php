<?php 
// koneksi database
session_start();
include 'koneksi.php';
 
// menangkap data id yang di kirim dari url
$id = $_GET['id'];
$rekam = mysqli_query($koneksi, "select * from rm where id_pasien = '$id'");
while ($tampilrekam = mysqli_fetch_array($rekam)) {
    $linkrm = $tampilrekam['file_rm'];
    $file_to_delete = $linkrm;
    unlink($file_to_delete);
}
 
// menghapus data dari database
mysqli_query($koneksi,"delete from pasien where id_pasien='$id'");
mysqli_query($koneksi, "delete from rm where id_pasien = '$id'");
 
// mengalihkan halaman kembali ke index.php
header("Location: ../halaman/petugas/datapasien.php", true, 301);
?>