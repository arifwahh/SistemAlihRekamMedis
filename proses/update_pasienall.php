<?php
session_start();
include 'koneksi.php';

// Tangkap data pasien
$id_pasien = $_POST['id_pasien'];
$nik_pasien = $_POST['nik_pasien'];
$nama_pasien = $_POST['nama_pasien'];
$nama_kk_pasien = $_POST['nama_kk_pasien'];
$jenis_kelamin_pasien = $_POST['jenis_kelamin_pasien'];
$pekerjaan_pasien = $_POST['pekerjaan_pasien'];
$tanggal_lahir_pasien = $_POST['tanggal_lahir_pasien'];
$agama_pasien = $_POST['agama_pasien'];
$alamat_pasien = $_POST['alamat_pasien'];
$nobpjs_pasien = $_POST['nobpjs_pasien'];

// Update data pasien
mysqli_query($koneksi, "UPDATE pasien SET 
    nik_pasien='$nik_pasien',
    nama_pasien='$nama_pasien',
    nama_kk_pasien='$nama_kk_pasien',
    jenis_kelamin_pasien='$jenis_kelamin_pasien',
    pekerjaan_pasien='$pekerjaan_pasien',
    tanggal_lahir_pasien='$tanggal_lahir_pasien',
    agama_pasien='$agama_pasien',
    alamat_pasien='$alamat_pasien',
    no_bpjs_pasien='$nobpjs_pasien'
    WHERE id_pasien='$id_pasien'
");

// Tangkap data kunjungan
$id_kunjungan_arr = $_POST['id_kunjungan'];
$tanggal_kunjungan_arr = $_POST['tanggalkunjungan'];
$keluhan_kunjungan_arr = $_POST['keluhankunjungan'];
$poli_kunjungan_arr = $_POST['polikunjungan'];
$biaya_arr = $_POST['biaya'];
$nobpjs_arr = $_POST['nobpjs'];

for ($i = 0; $i < count($keluhan_kunjungan_arr); $i++) {
    $id_kunjungan = $id_kunjungan_arr[$i];
    $tanggal_kunjungan = $tanggal_kunjungan_arr[$i];
    $keluhan_kunjungan = $keluhan_kunjungan_arr[$i];
    $poli_kunjungan = $poli_kunjungan_arr[$i];
    $biaya = $biaya_arr[$i];
    $nobpjs = $nobpjs_arr[$i];

    if (trim($keluhan_kunjungan) == '' && trim($tanggal_kunjungan) == '') continue;

    if (!empty($id_kunjungan)) {
        // Update kunjungan lama
        mysqli_query($koneksi, "UPDATE kunjungan SET 
            tanggal_kunjungan='$tanggal_kunjungan',
            keluhan_kunjungan='$keluhan_kunjungan',
            poli_kunjungan='$poli_kunjungan',
            biaya_kunjungan='$biaya',
            no_bpjs_kunjungan='$nobpjs'
            WHERE id_kunjungan='$id_kunjungan' AND id_pasien='$id_pasien'
        ");
    } else {
        mysqli_query($koneksi, "INSERT INTO kunjungan 
            (id_pasien, tanggal_kunjungan, keluhan_kunjungan, poli_kunjungan, biaya_kunjungan, no_bpjs_kunjungan)
            VALUES 
            ('$id_pasien', '$tanggal_kunjungan', '$keluhan_kunjungan', '$poli_kunjungan', '$biaya', '$nobpjs')
        ");
    }
}

// Tangkap data RM
$no_rm = isset($_POST['no_rm']) ? $_POST['no_rm'] : '';
$idpengguna = $_SESSION['idpengguna'];
$namaFile = isset($_FILES['nama_file_pdf']['name']) ? $_FILES['nama_file_pdf']['name'] : '';
$file_tmp = isset($_FILES['nama_file_pdf']['tmp_name']) ? $_FILES['nama_file_pdf']['tmp_name'] : '';
$dirUpload = "../assets/pdfrm/";
$linkBerkas = '';

if (!empty($namaFile)) {
    $linkBerkas = $dirUpload . uniqid() . '_' . basename($namaFile);
    move_uploaded_file($file_tmp, $linkBerkas);
}

// Ambil id_kunjungan terakhir
$get_kunjungan = mysqli_query($koneksi, "SELECT id_kunjungan FROM kunjungan WHERE id_pasien='$id_pasien' ORDER BY tanggal_kunjungan DESC, id_kunjungan DESC LIMIT 1");
$data_kunjungan = mysqli_fetch_assoc($get_kunjungan);
$id_kunjungan_terakhir = $data_kunjungan ? $data_kunjungan['id_kunjungan'] : null;

// Cek apakah sudah ada data RM
$cek_rm = mysqli_query($koneksi, "SELECT * FROM rm WHERE id_pasien='$id_pasien' LIMIT 1");
$tanggalSekarang = date('Y-m-d H:i:s');
if (mysqli_num_rows($cek_rm) > 0) {
    $data_rm = mysqli_fetch_assoc($cek_rm);

    // Cek status musnah/retensi
    if (
        isset($data_rm['status']) &&
        in_array(strtolower($data_rm['status']), ['musnah', 'retensi']) &&
        $id_kunjungan_terakhir
    ) {
        // Ambil tanggal kunjungan terakhir
        $get_tgl_kunjungan = mysqli_query($koneksi, "SELECT tanggal_kunjungan FROM kunjungan WHERE id_kunjungan='$id_kunjungan_terakhir' LIMIT 1");
        $data_tgl_kunjungan = mysqli_fetch_assoc($get_tgl_kunjungan);
        if ($data_tgl_kunjungan) {
            $tgl_kunjungan_terakhir = $data_tgl_kunjungan['tanggal_kunjungan'];
            $dua_tahun_lalu = date('Y-m-d', strtotime('-2 years'));
            if ($tgl_kunjungan_terakhir > $dua_tahun_lalu) {
                // Ubah status menjadi '-'
                mysqli_query($koneksi, "UPDATE rm SET status='-' WHERE id_pasien='$id_pasien'");
            }
        }
    }

    // Update RM
    $update_rm_sql = "UPDATE rm SET 
        no_rm='$no_rm',
        id_petugas_input='$idpengguna',
        id_kunjungan_terakhir=" . ($id_kunjungan_terakhir ? "'$id_kunjungan_terakhir'" : "NULL") . ",
        tanggal_status='$tanggalSekarang'";
    if (!empty($linkBerkas)) {
        $update_rm_sql .= ", file_rm='$linkBerkas'";
    }
    $update_rm_sql .= " WHERE id_pasien='$id_pasien'";
    mysqli_query($koneksi, $update_rm_sql);
} else {
    // Insert RM baru
    mysqli_query($koneksi, "INSERT INTO rm 
        (no_rm, id_pasien, id_petugas_input, id_kunjungan_terakhir, file_rm, tanggal_status)
        VALUES 
        ('$no_rm', '$id_pasien', '$idpengguna', " . ($id_kunjungan_terakhir ? "'$id_kunjungan_terakhir'" : "NULL") . ", '$linkBerkas', '$tanggalSekarang')
    ");
}

// Redirect kembali ke halaman data pasien
header("Location: ../halaman/petugas/datapasien.php");
exit();
?>
