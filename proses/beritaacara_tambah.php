<?php
// Koneksi ke database
include 'koneksi.php';
   
// Ambil data dari form POST
$id_ba             = $_POST['id_ba'];
$tanggal_ba        = $_POST['tanggal_ba'];
$judul_ba          = $_POST['judul_ba'];
$pj_apoteker_ba    = $_POST['pj_apoteker_ba'];
$pj_nip_ba         = $_POST['pj_nip_ba'];
$saksi_ba          = $_POST['saksi_ba'];
$saksi_nip_ba      = $_POST['saksi_nip_ba'];
$saksi_jabatan_ba  = $_POST['saksi_jabatan_ba'];
$tipe_ba           = $_POST['tipe_ba'];
$periodeawal_ba    = !empty($_POST['periodeawal_ba']) ? $_POST['periodeawal_ba'] : NULL;
$periodeakhir_ba   = !empty($_POST['periodeakhir_ba']) ? $_POST['periodeakhir_ba'] : NULL;

// Query insert
$sql = "INSERT INTO berita_acara (
    id_ba, tanggal_ba, judul_ba, pj_apoteker_ba, pj_nip_ba, 
    saksi_ba, saksi_nip_ba, saksi_jabatan_ba, tipe_ba, 
    periodeawal_ba, periodeakhir_ba
) VALUES (
    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
)";

$stmt = $koneksi->prepare($sql);
$stmt->bind_param(
    "sssssssssss",
    $id_ba,
    $tanggal_ba,
    $judul_ba,
    $pj_apoteker_ba,
    $pj_nip_ba,
    $saksi_ba,
    $saksi_nip_ba,
    $saksi_jabatan_ba,
    $tipe_ba,
    $periodeawal_ba,
    $periodeakhir_ba
);

if ($stmt->execute()) {
    // Redirect atau pesan sukses
    header("Location: ../halaman/petugas/beritaacara.php?status=sukses");
    exit();
} else {
    // Pesan error
    echo "Gagal menambah data: " . $stmt->error;
}

$stmt->close();
$koneksi->close();
?>