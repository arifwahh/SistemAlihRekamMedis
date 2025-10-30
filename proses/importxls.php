<?php
use PhpOffice\PhpSpreadsheet\IOFactory;

session_start();
include 'koneksi.php';

require '../vendor/autoload.php'; // sesuaikan path jika perlu

if (isset($_FILES['file_excel']) && $_FILES['file_excel']['error'] == 0) {
    $file_tmp = $_FILES['file_excel']['tmp_name'];
    $spreadsheet = IOFactory::load($file_tmp);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    // Mulai dari baris ke-2 (baris pertama = header)
    for ($i = 1; $i < count($rows); $i++) {
        $row = $rows[$i];
        // Pastikan jumlah kolom sesuai template
        if (count($row) < 15) continue;

        $nik_pasien = trim($row[0]);
        $nama_pasien = trim($row[1]);
        $namakk_pasien = trim($row[2]);
        $jeniskelamin_pasien = trim($row[3]);
        $pekerjaan_pasien = trim($row[4]);
        $tanggallahir_pasien = trim($row[5]);
        $agama_pasien = trim($row[6]);
        $alamat_pasien = trim($row[7]);
        $tanggal_kunjungan = trim($row[8]);
        $diagnosa = trim($row[9]);
        $poli = trim($row[10]);
        $biaya = trim($row[12]);
        $no_bpjs = trim($row[13]);
        $norm = trim($row[14]);

        // Insert pasien dengan id increment manual (ambil id maksimal lalu tambah 1)
        $cek = mysqli_query($koneksi, "SELECT id_pasien FROM pasien WHERE nik_pasien='$nik_pasien'");
        if (mysqli_num_rows($cek) == 0) {
            $result_max = mysqli_query($koneksi, "SELECT MAX(id_pasien) AS max_id FROM pasien");
            $data_max = mysqli_fetch_assoc($result_max);
            $next_id = ($data_max['max_id'] ?? 0) + 1;
            mysqli_query($koneksi, "INSERT INTO pasien VALUES('$next_id', '$nik_pasien', '$nama_pasien', '$namakk_pasien', '$jeniskelamin_pasien', '$pekerjaan_pasien', '$tanggallahir_pasien', '$agama_pasien', '$alamat_pasien')");
            $id_pasien = $next_id;
        } else {
            $row_pasien = mysqli_fetch_assoc($cek);
            $id_pasien = $row_pasien['id_pasien'];
        }

        // Multi kunjungan (jika ada koma)
        $arr_tanggal = array_map('trim', explode(',', $tanggal_kunjungan));
        $arr_diagnosa = array_map('trim', explode(',', $diagnosa));
        $arr_poli = array_map('trim', explode(',', $poli));
        $arr_biaya = array_map('trim', explode(',', $biaya));
        $arr_nobpjs = array_map('trim', explode(',', $no_bpjs));
        $max = max(count($arr_tanggal), count($arr_diagnosa), count($arr_poli), count($arr_biaya), count($arr_nobpjs));

        for ($j = 0; $j < $max; $j++) {
            $tgl = $arr_tanggal[$j] ?? '';
            $diag = $arr_diagnosa[$j] ?? '';
            $pol = $arr_poli[$j] ?? '';
            $by = $arr_biaya[$j] ?? '';
            $nbpjs = $arr_nobpjs[$j] ?? '';

            if ($tgl != '') {
                mysqli_query($koneksi, "INSERT INTO kunjungan (id_kunjungan, id_pasien, tanggal_kunjungan, keluhan_kunjungan, poli_kunjungan, biaya_kunjungan, no_bpjs_kunjungan) VALUES ('', '$id_pasien', '$tgl', '$diag', '$pol', '$by', '$nbpjs')");
                $id_kunjungan = mysqli_insert_id($koneksi);
            }
        
        }
        // Insert ke rm (tanpa file PDF)
                $idpengguna = $_SESSION['idpengguna'] ?? 0;
                date_default_timezone_set('Asia/Jakarta');
                $tanggalSekarang = date('Y-m-d H:i:s');
                mysqli_query($koneksi, "INSERT INTO rm VALUES ('', '$norm', '$id_pasien', '$idpengguna', '$id_kunjungan', '-', '', '$tanggalSekarang', '-')");
    }
    header("Location: ../halaman/petugas/datapasien.php");
    exit();
} else {
    echo "File tidak ditemukan atau upload gagal.";
}
?>
