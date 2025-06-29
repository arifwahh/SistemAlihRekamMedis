<?php
// Koneksi ke database
include 'koneksi.php';

// Ambil data dari form
$id_pengguna    = isset($_POST['id_pengguna']) ? intval($_POST['id_pengguna']) : 0;
$email_pengguna = isset($_POST['email_pengguna']) ? trim($_POST['email_pengguna']) : '';
$nama_pengguna  = isset($_POST['nama_pengguna']) ? trim($_POST['nama_pengguna']) : '';
$jabatan_pengguna = isset($_POST['jabatan_pengguna']) ? trim($_POST['jabatan_pengguna']) : '';
$password_pengguna = isset($_POST['password_pengguna']) ? $_POST['password_pengguna'] : '';
$cropped_image  = isset($_POST['cropped_image']) ? $_POST['cropped_image'] : '';

// Validasi sederhana
if ($id_pengguna == 0 || $email_pengguna == '' || $nama_pengguna == '' || $jabatan_pengguna == '') {
    header("Location: ../pengguna.php?msg=error");
    exit;
}

// Proses upload foto jika ada gambar baru
$foto_pengguna = '';
if (!empty($cropped_image)) {
    $img = str_replace('data:image/png;base64,', '', $cropped_image);
    $img = str_replace(' ', '+', $img);
    $data = base64_decode($img);
    $foto_pengguna = 'pengguna_' . $id_pengguna . '_' . time() . '.png';
    $file_path = '../assets/fotopengguna/' . $foto_pengguna;
    file_put_contents($file_path, $data);

    // Hapus foto lama jika ada (abaikan ekstensi, hapus semua file dengan judul yang sama)
    $q = mysqli_query($koneksi, "SELECT foto_pengguna FROM pengguna WHERE id_pengguna='$id_pengguna'");
    $d = mysqli_fetch_assoc($q);
    if (!empty($d['foto_pengguna'])) {
        $judul = pathinfo($d['foto_pengguna'], PATHINFO_FILENAME);
        $pattern = '../assets/fotopengguna/' . $judul . '.*';
        foreach (glob($pattern) as $oldFile) {
            @unlink($oldFile);
        }
    }
}

// Siapkan query update
$update_fields = [];
$update_fields[] = "email_pengguna='" . mysqli_real_escape_string($koneksi, $email_pengguna) . "'";
$update_fields[] = "nama_pengguna='" . mysqli_real_escape_string($koneksi, $nama_pengguna) . "'";
$update_fields[] = "jabatan_pengguna='" . mysqli_real_escape_string($koneksi, $jabatan_pengguna) . "'";

if (!empty($password_pengguna)) {
    $password_hash = $password_pengguna;
    $update_fields[] = "password_pengguna='" . mysqli_real_escape_string($koneksi, $password_hash) . "'";
}

if (!empty($foto_pengguna)) {
    $update_fields[] = "foto_pengguna='" . mysqli_real_escape_string($koneksi, $foto_pengguna) . "'";
}

$update_sql = "UPDATE pengguna SET " . implode(', ', $update_fields) . " WHERE id_pengguna='$id_pengguna'";

// Eksekusi query
if (mysqli_query($koneksi, $update_sql)) {
    header("Location: ../halaman/petugas/datapengguna.php?msg=success");
} else {
    header("Location: ../halaman/petugas/datapengguna.php?msg=error");
}
exit;
?>