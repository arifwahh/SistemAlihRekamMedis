<?php
session_start();
// koneksi database
include 'koneksi.php';
// // menangkap data pasien
$nik_pasien = $_POST['nik'];
$nama_pasien = $_POST['namapasien'];
$namakk_pasien = $_POST['namakkpasien'];
$jeniskelamin_pasien = $_POST['jeniskelaminpasien'];
$pekerjaan_pasien = $_POST['pekerjaanpasien'];
$tanggallahir_pasien = $_POST['tanggallahirpasien'];
$agama_pasien = $_POST['agamapasien'];
$nobpjs_pasien = $_POST['nobpjs_pasien'];
$alamat_pasien = $_POST['alamatpasien'];
$halaman_disimpan = isset($_POST['halaman_disimpan']) ? 1 : 0;
$no_halaman_disimpan = isset($_POST['no_halaman_disimpan']) && $_POST['no_halaman_disimpan'] !== '' ? $_POST['no_halaman_disimpan'] : '-';
// //menangkap data kunjungan
$number = count($_POST["keluhankunjungan"]);

// // //menangkap data rm
$namaFile = $_FILES['nama_file_pdf']['name'];
$x = explode('.', $namaFile);
$ekstensiFile = strtolower(end($x));
$ukuranFile    = $_FILES['nama_file_pdf']['size'];
$file_tmp = $_FILES['nama_file_pdf']['tmp_name'];
// Lokasi Penempatan file
$dirUpload = "../assets/pdfrm/";
$linkBerkas = $dirUpload.$namaFile;
// menginput data ke database
mysqli_query($koneksi, "insert into pasien values('','$nik_pasien','$nama_pasien','$namakk_pasien','$jeniskelamin_pasien','$pekerjaan_pasien','$tanggallahir_pasien','$agama_pasien','$alamat_pasien','$nobpjs_pasien')");

if($number > 0)  
 {  
      for($i=0; $i<$number; $i++)  
      {  
           if(trim($_POST["keluhankunjungan"][$i] != ''))  
           {  
                $fetchidpasien = mysqli_query($koneksi, "select * from pasien where nik_pasien = '$nik_pasien'");
                $getidpasien = mysqli_fetch_array($fetchidpasien);
                $idpasien = $getidpasien['id_pasien'];
                $arr = $_POST['keluhankunjungan'][$i];
                $addkunjungan = "INSERT INTO kunjungan (id_kunjungan,id_pasien,tanggal_kunjungan,keluhan_kunjungan,poli_kunjungan,klinik_kunjungan,biaya_kunjungan,no_bpjs_kunjungan) VALUES('".mysqli_real_escape_string($koneksi, $_POST[""][$i])."','".$idpasien."','".$_POST['tanggalkunjungan'][$i]."','".$_POST['keluhankunjungan'][$i]."','".$_POST['polikunjungan'][$i]."','".$_POST['klinikkunjungan'][$i]."','".$_POST['biaya'][$i]."','".$_POST['nobpjs'][$i]."')";  
                mysqli_query($koneksi, $addkunjungan);  
           }  
      }  
      //jika berhasil input
      $fetchidkunjungan = mysqli_query($koneksi, "SELECT * FROM `kunjungan` WHERE id_pasien = '$idpasien' ORDER BY tanggal_kunjungan DESC LIMIT 1;");
      $getidkunjungan = mysqli_fetch_array($fetchidkunjungan);
      $idkunjungan = $getidkunjungan['id_kunjungan'];
      $norm = $_POST['norm'];
      $idpengguna = $_SESSION['idpengguna'];
      echo $idpengguna;
      $terupload = move_uploaded_file($file_tmp, $linkBerkas);
      date_default_timezone_set('Asia/Jakarta');
      $tanggalSekarang = date('Y-m-d H:i:s');
      $addrm = mysqli_query($koneksi,"INSERT INTO rm VALUES ('','$norm','$idpasien','$idpengguna','$idkunjungan','-','$linkBerkas','$tanggalSekarang','$no_halaman_disimpan')");
      if ($terupload && $addrm == 1) {
          header("Location: ../halaman/petugas/datapasien.php", true, 301);
          exit();
      } else {
          header("Location: ../halaman/petugas/datapasien.php", true, 301);
          exit();
      }
 }  
 else 
 {  
      //jika tidak berhasil
      echo "Please Enter Name";  
 }  

 ?>
