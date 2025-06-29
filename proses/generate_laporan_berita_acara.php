<?php
require '../vendor/autoload.php'; // pastikan composer autoload sudah ada dan mPDF terinstall
require 'koneksi.php'; // impor koneksi.php
require 'tanggalindo.php'; // impor tanggalindo.php

use Mpdf\Mpdf;

// Mapping kategori pencarian
$kategori_map = [
    'tanggal_ba' => "tanggal_ba",
    'judul_ba' => "judul_ba",
    'pj_apoteker_ba' => "pj_apoteker_ba"
];

// Handle search
$where = "WHERE 1=1";
if (!empty($_GET['kategori']) && !empty($_GET['keyword']) && isset($kategori_map[$_GET['kategori']])) {
    $kategori = mysqli_real_escape_string($koneksi, $_GET['kategori']);
    $keyword = mysqli_real_escape_string($koneksi, $_GET['keyword']);
    $where .= " AND {$kategori_map[$kategori]} LIKE '%$keyword%'";
}

$sql = "SELECT * FROM berita_acara $where ORDER BY tanggal_ba DESC";
$data = mysqli_query($koneksi, $sql);

// Mulai HTML untuk PDF
$html = '<h2 style="text-align:center;">Laporan Berita Acara</h2>';
$html .= '<table border="1" cellpadding="6" cellspacing="0" width="100%">
<thead>
<tr style="background:#eee;">
    <th>No</th>
    <th>Tanggal Pelaksanaan</th>
    <th>Judul</th>
    <th>Penanggung Jawab</th>
</tr>
</thead>
<tbody>';

$no = 1;
while ($d = mysqli_fetch_array($data)) {
    $html .= "<tr>
        <td>{$no}</td>
        <td>" . tgl_indo($d['tanggal_ba']) . "</td>
        <td>" . htmlspecialchars($d['judul_ba']) . "</td>
        <td>" . htmlspecialchars($d['pj_apoteker_ba']) . "</td>
    </tr>";
    $no++;
}
$html .= '</tbody></table>';

// Generate PDF
$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4',
    'margin_left' => 10,
    'margin_right' => 10,
    'margin_top' => 16,
    'margin_bottom' => 16,
    'margin_header' => 9,
    'margin_footer' => 9
]);
$mpdf->SetTitle('Laporan Berita Acara');
$mpdf->SetAuthor('Puskesmas Kalivates');
$mpdf->WriteHTML($html);
$mpdf->Output('laporan_berita_acara.pdf', 'I');
exit;
