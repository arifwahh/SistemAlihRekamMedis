<?php 
// koneksi database
session_start();
include 'koneksi.php';

// menangkap data id_pengguna yang dikirim dari url
if (isset($_GET['hapus'])) {
    $id_pengguna = $_GET['hapus'];

    // Ambil nama file foto (tanpa ekstensi) dari database
    $query = mysqli_query($koneksi, "SELECT foto_pengguna FROM pengguna WHERE id_pengguna = '$id_pengguna'");
    $data = mysqli_fetch_assoc($query);

    if (!empty($data['foto_pengguna'])) {
        // Ambil nama file tanpa ekstensi
        $filename_no_ext = pathinfo($data['foto_pengguna'], PATHINFO_FILENAME);

        // Folder tempat foto disimpan
        $folder = "../assets/fotopengguna/";

        // Cari semua file yang cocok dengan nama tanpa ekstensi
        foreach (glob($folder . $filename_no_ext . '.*') as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    // Hapus data pengguna dari database
    mysqli_query($koneksi, "DELETE FROM pengguna WHERE id_pengguna = '$id_pengguna'");
}

// Redirect kembali ke halaman data pengguna
header("Location: ../halaman/petugas/datapengguna.php", true, 301);
?>