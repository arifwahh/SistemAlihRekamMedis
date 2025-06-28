<?php
// Koneksi ke database
include 'koneksi.php';

// Cek apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $id_ba = $_POST['id_ba'];
    $judul_ba = mysqli_real_escape_string($koneksi, $_POST['judul_ba']);
    $tanggal_ba = mysqli_real_escape_string($koneksi, $_POST['tanggal_ba']);
    $pj_apoteker_ba = mysqli_real_escape_string($koneksi, $_POST['pj_apoteker_ba']);
    $pj_nip_ba = mysqli_real_escape_string($koneksi, $_POST['pj_nip_ba']);
    $saksi_ba = mysqli_real_escape_string($koneksi, $_POST['saksi_ba']);
    $saksi_nip_ba = mysqli_real_escape_string($koneksi, $_POST['saksi_nip_ba']);
    $saksi_jabatan_ba = mysqli_real_escape_string($koneksi, $_POST['saksi_jabatan_ba']);
    $tipe_ba = mysqli_real_escape_string($koneksi, $_POST['tipe_ba']);
    $periodeawal_ba = !empty($_POST['periodeawal_ba']) ? mysqli_real_escape_string($koneksi, $_POST['periodeawal_ba']) : NULL;
    $periodeakhir_ba = !empty($_POST['periodeakhir_ba']) ? mysqli_real_escape_string($koneksi, $_POST['periodeakhir_ba']) : NULL;

    // Query update berita acara
    $sql = "UPDATE berita_acara SET 
                judul_ba = '$judul_ba',
                tanggal_ba = '$tanggal_ba',
                pj_apoteker_ba = '$pj_apoteker_ba',
                pj_nip_ba = '$pj_nip_ba',
                saksi_ba = '$saksi_ba',
                saksi_nip_ba = '$saksi_nip_ba',
                saksi_jabatan_ba = '$saksi_jabatan_ba',
                tipe_ba = '$tipe_ba',
                periodeawal_ba = " . ($periodeawal_ba ? "'$periodeawal_ba'" : "NULL") . ",
                periodeakhir_ba = " . ($periodeakhir_ba ? "'$periodeakhir_ba'" : "NULL") . "
            WHERE id_ba = '$id_ba'";

    if (mysqli_query($koneksi, $sql)) {
        // Redirect ke halaman daftar berita acara setelah berhasil update
        header("Location: ../halaman/petugas/beritaacara.php?pesan=update_sukses");
        exit();
    } else {
        echo "Gagal mengupdate data: " . mysqli_error($koneksi);
    }
} else {
    // Jika tidak ada data POST, redirect ke halaman utama
    header("Location: ../halaman/petugas/beritaacara.php");
    exit();
}
?>