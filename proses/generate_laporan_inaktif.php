<?php
require '../vendor/autoload.php'; // pastikan composer autoload sudah ada dan mPDF terinstall
require 'koneksi.php'; // impor koneksi.php
require 'tanggalindo.php'; // impor tanggalindo.php

use Mpdf\Mpdf;

// Query dasar
$query = "SELECT 
            a.id_pasien,
            a.nama_pasien,
            a.tanggal_lahir_pasien,
            a.jenis_kelamin_pasien,
            a.alamat_pasien,
            c.status,
            c.no_rm,
            MAX(b.tanggal_kunjungan) AS terakhir_kunjungan
        FROM pasien a
        JOIN kunjungan b ON a.id_pasien = b.id_pasien
        JOIN rm c ON a.id_pasien = c.id_pasien
        GROUP BY a.id_pasien
        HAVING terakhir_kunjungan < DATE_SUB(CURDATE(), INTERVAL 2 YEAR)
        AND c.status = '-'";

// Fitur pencarian
if (isset($_GET['kategori']) && isset($_GET['keyword']) && $_GET['keyword'] != '') {
    $kategori = $_GET['kategori'];
    $keyword = mysqli_real_escape_string($koneksi, $_GET['keyword']);
    // Map kategori ke alias kolom
    $allowed = [
        'no_rm' => 'c.no_rm',
        'nama_pasien' => 'a.nama_pasien',
        'tanggal_lahir_pasien' => 'a.tanggal_lahir_pasien',
        'jenis_kelamin_pasien' => 'a.jenis_kelamin_pasien',
        'alamat_pasien' => 'a.alamat_pasien',
        'terakhir_kunjungan' => 'terakhir_kunjungan'
    ];
    if (array_key_exists($kategori, $allowed)) {
        if ($kategori == 'terakhir_kunjungan' || $kategori == 'tanggal_lahir_pasien') {
            $query .= " AND DATE(" . $allowed[$kategori] . ") LIKE '%$keyword%'";
        } else {
            $query .= " AND " . $allowed[$kategori] . " LIKE '%$keyword%'";
        }
    }
}

$query .= " ORDER BY terakhir_kunjungan DESC;";

$data = mysqli_query($koneksi, $query);

// Mulai HTML untuk PDF
$html = '<h2 style="text-align:center;">Laporan Pasien Inaktif</h2>';
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
    $html .= "<tr>
        <td>{$no}</td>
        <td>{$d['no_rm']}</td>
        <td>{$d['nama_pasien']}</td>
        <td>".tgl_indo($d['tanggal_lahir_pasien'])."</td>
        <td>{$d['jenis_kelamin_pasien']}</td>
        <td>{$d['alamat_pasien']}</td>
        <td>".tgl_indo($d['terakhir_kunjungan'])."</td>
        <td>INAKTIF</td>
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
$mpdf->SetTitle('Laporan Pasien Inaktif');
$mpdf->SetAuthor('Puskesmas Kalivates');
$mpdf->WriteHTML($html);
$mpdf->Output('laporan_pasien_inaktif.pdf', 'I');
exit;
