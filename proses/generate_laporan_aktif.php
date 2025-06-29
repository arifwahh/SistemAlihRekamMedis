<?php
require '../vendor/autoload.php'; // pastikan composer autoload sudah ada dan mPDF terinstall
require 'koneksi.php'; // impor koneksi.php
require 'tanggalindo.php'; // impor tanggalindo.php

use Mpdf\Mpdf;
$where = "WHERE b.tanggal_kunjungan > DATE_SUB(CURDATE(), INTERVAL 2 YEAR)";
$join = "JOIN kunjungan b ON a.id_pasien = b.id_pasien";
$kategori_map = [
    'no_rm' => "rm.no_rm",
    'nama_pasien' => "a.nama_pasien",
    'tanggal_lahir_pasien' => "a.tanggal_lahir_pasien",
    'jenis_kelamin_pasien' => "a.jenis_kelamin_pasien",
    'alamat_pasien' => "a.alamat_pasien"
];

if (!empty($_GET['kategori']) && !empty($_GET['keyword']) && isset($kategori_map[$_GET['kategori']])) {
    $kategori = mysqli_real_escape_string($koneksi, $_GET['kategori']);
    $keyword = mysqli_real_escape_string($koneksi, $_GET['keyword']);
    if($kategori == 'no_rm') {
        $join .= " LEFT JOIN rm ON a.id_pasien = rm.id_pasien";
        $where .= " AND rm.no_rm LIKE '%$keyword%'";
    } else {
        $where .= " AND {$kategori_map[$kategori]} LIKE '%$keyword%'";
    }
}

$sql = "SELECT DISTINCT a.id_pasien, a.nama_pasien, a.tanggal_lahir_pasien, a.jenis_kelamin_pasien, a.alamat_pasien 
        FROM pasien a $join $where ORDER BY b.tanggal_kunjungan DESC";
$data = mysqli_query($koneksi, $sql);

// Mulai HTML untuk PDF
$html = '<h2 style="text-align:center;">Laporan Pasien Aktif</h2>';
$html .= '<table border="1" cellpadding="6" cellspacing="0" width="100%">
<thead>
<tr style="background:#eee;">
    <th>No</th>
    <th>No RM</th>
    <th>Nama Pasien</th>
    <th>Tanggal Lahir</th>
    <th>Gender</th>
    <th>Alamat</th>
    <th>Tgl Kunjungan Terakhir</th>
    <th>Status</th>
</tr>
</thead>
<tbody>';

$no = 1;
while ($d = mysqli_fetch_array($data)) {
    $idpasien = $d['id_pasien'];
    // Ambil No RM
    $rekamid = '';
    $linkrm = '';
    $rekam = mysqli_query($koneksi, "SELECT * FROM rm WHERE id_pasien = '$idpasien'");
    if ($tampilrekam = mysqli_fetch_array($rekam)) {
        $rekamid = $tampilrekam['no_rm'];
        $linkrm = $tampilrekam['file_rm'];
    }
    // Ambil kunjungan terakhir
    $kunjungan = mysqli_query($koneksi, "SELECT MAX(tanggal_kunjungan) as knj FROM kunjungan WHERE id_pasien = '$idpasien'");
    $tgl_kunjungan = '';
    if ($tampilkunjungan = mysqli_fetch_array($kunjungan)) {
        $tgl_kunjungan = tgl_indo($tampilkunjungan['knj']);
    }
    $html .= "<tr>
        <td>{$no}</td>
        <td>{$rekamid}</td>
        <td>{$d['nama_pasien']}</td>
        <td>".tgl_indo($d['tanggal_lahir_pasien'])."</td>
        <td>{$d['jenis_kelamin_pasien']}</td>
        <td>{$d['alamat_pasien']}</td>
        <td>{$tgl_kunjungan}</td>
        <td>AKTIF</td>
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
$mpdf->SetTitle('Laporan Pasien Aktif');
$mpdf->SetAuthor('Puskesmas Kalivates');
$mpdf->WriteHTML($html);
$mpdf->Output('laporan_pasien_aktif.pdf', 'I');
exit;
