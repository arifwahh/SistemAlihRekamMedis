<?php 
// mengaktifkan session pada php
session_start();
 
// menghubungkan php dengan koneksi database
include 'koneksi.php';
 
// menangkap data yang dikirim dari form login
$email = $_POST['email'];
$password = $_POST['password'];

// Gunakan prepared statement untuk mencegah SQL injection
$stmt = $koneksi->prepare("SELECT * FROM pengguna WHERE email_pengguna = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$cek = $result->num_rows;

// cek apakah username dan password di temukan pada database
if($cek > 0){

	$data = $result->fetch_assoc();
	$nama = $data['nama_pengguna'];
 
	// cek jika user login sebagai admin
	if($data['jabatan_pengguna']=="petugas"){
 
		// buat session login dan username
		$_SESSION['email_pengguna'] = $email;
		$_SESSION['nama_pengguna'] = $nama;
		$_SESSION['idpengguna'] = $data['id_pengguna'];
		$_SESSION['jabatan_pengguna'] = "petugas";
		$_SESSION['status'] = "login";
		// alihkan ke halaman dashboard admin
		header("location:../halaman/petugas/home.php");
 
	// cek jika user login sebagai pegawai
	}else if($data['jabatan_pengguna']=="kepala"){
		// buat session login dan username
		$_SESSION['email_pengguna'] = $email;
		$_SESSION['nama_pengguna'] = $nama;
		$_SESSION['idpengguna'] = $data['id_pengguna'];
		$_SESSION['jabatan_pengguna'] = "kepala";
		$_SESSION['status'] = "login";
		// alihkan ke halaman dashboard pegawai
		header("location:../halaman/kepala/home.php");
 
	}else{
 
		// alihkan ke halaman login kembali
		header("location:../halaman/login.php?pesan=gagal");
	}	
}else{
	header("location:../halaman/login.php?pesan=gagal");
}
?>