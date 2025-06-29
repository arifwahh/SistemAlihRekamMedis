<?php
include 'koneksi.php'; // sesuaikan path jika perlu

// Tangkap data dari form
$email = $_POST['email_pengguna'];
$password = $_POST['password_pengguna'];
$nama = $_POST['nama_pengguna'];
$jabatan = $_POST['jabatan_pengguna'];
$cropped_image = $_POST['cropped_image'];

// // Hash password (opsional, tapi direkomendasikan)
// $password_hash = password_hash($password, PASSWORD_DEFAULT);

// Proses simpan foto (base64 ke file)
$foto_nama = null;
if (!empty($cropped_image)) {
    $folder = "../assets/fotopengguna/";
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }
    $img = str_replace('data:image/png;base64,', '', $cropped_image);
    $img = str_replace(' ', '+', $img);
    $data = base64_decode($img);
    $foto_nama = uniqid('foto_') . '.png';
    file_put_contents($folder . $foto_nama, $data);
}
$query = "SELECT MAX(id_pengguna) AS max_id FROM pengguna";
$result = $koneksi->query($query);
$row = $result->fetch_assoc();
$next_id = ($row['max_id'] ?? 0) + 1;
// Insert ke database
$sql = "INSERT INTO pengguna (id_pengguna, email_pengguna, password_pengguna, nama_pengguna, jabatan_pengguna, foto_pengguna)
    VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("isssss", $next_id, $email, $password, $nama, $jabatan, $foto_nama);
if ($stmt->execute()) {
    header("Location: ../halaman/petugas/datapengguna.php?status=sukses");
    exit();
} else {
    echo "Gagal menambah pengguna: " . $stmt->error;
}
$stmt->close();
$koneksi->close();
?>