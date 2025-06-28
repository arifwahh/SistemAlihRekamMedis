<?php
require '../vendor/autoload.php'; // pastikan composer autoload sudah ada dan mPDF terinstall
require 'koneksi.php'; // impor koneksi.php
require 'tanggalindo.php'; // impor tanggalindo.php

use Mpdf\Mpdf;

// Ambil data dari form GET
$tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : null;
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : null;

// Validasi input tanggal
if (!$tanggal_awal || !$tanggal_akhir) {
    echo "Tanggal awal dan akhir harus diisi.";
    exit;
}

// Ambil data rm
$sql = "SELECT * FROM rm WHERE status = 'RETENSI' AND tanggal_status BETWEEN '$tanggal_awal' AND '$tanggal_akhir' ORDER BY tanggal_status ASC";
$result = $koneksi->query($sql);

if ($result && $result->num_rows > 0) {
    // Ambil semua no_rm dari hasil query
    $noRmList = [];
    while ($row = $result->fetch_assoc()) {
        $noRmList[] = $row['no_rm'];
    }
    $idList = implode("','", $noRmList);

    // Query detail pasien berdasarkan no_rm yang sudah didapat
    $query = mysqli_query($koneksi, "SELECT a.no_rm, b.nama_pasien, b.tanggal_lahir_pasien, b.jenis_kelamin_pasien, b.alamat_pasien 
                                     FROM rm a JOIN pasien b ON a.id_pasien = b.id_pasien 
                                     WHERE a.no_rm IN ('$idList')");

    // Buat isi PDF
    $html = '<h2 style="text-align:center;">Laporan Retensi Rekam Medis</h2>
    <p style="text-align:center;">Tanggal '.tgl_indo($tanggal_awal).' - '.tgl_indo($tanggal_akhir).'</p>';
    $html .= '<table border="1" cellpadding="8" cellspacing="0" width="100%"><tr><th>No</th><th>No RM</th><th>Nama Pasien</th><th>Tanggal Lahir</th><th>Jenis Kelamin</th><th>Alamat</th></tr>';
    
    $no = 1;
    while ($row = mysqli_fetch_array($query)) {
        $html .= "<tr>
                    <td>{$no}</td>
                    <td>{$row['no_rm']}</td>
                    <td>{$row['nama_pasien']}</td>
                    <td>".tgl_indo($row['tanggal_lahir_pasien'])."</td>
                    <td>{$row['jenis_kelamin_pasien']}</td>
                    <td>{$row['alamat_pasien']}</td>
                  </tr>";
        $no++;
    }
    $html .= '</table>';
    // HTML content with precise spacing
    // style inline untuk menghindari masalah dengan CSS eksternal
    // Buat PDF
    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'margin_left' => 15,
        'margin_right' => 15,
        'margin_top' => 16,
        'margin_bottom' => 16,
        'margin_header' => 9,
        'margin_footer' => 9
    ]);
    $mpdf->SetTitle('Laporan Retensi Rekam Medis');
    $mpdf->SetAuthor('Puskesmas Kalivates');
    $mpdf->WriteHTML($html); // $mpdf->Output('berita_acara_'.$row['id_ba'].'.pdf', 'D'); 
    echo $html;// tampilkan di browser
    echo '<script>window.print();</script>';
} else {
    echo "Data tidak ditemukan.";
    exit;
}
?>
