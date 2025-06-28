<?php
require '../vendor/autoload.php'; // pastikan composer autoload sudah ada dan mPDF terinstall
require 'koneksi.php'; // impor koneksi.php
require 'tanggalindo.php'; // impor tanggalindo.php

use Mpdf\Mpdf;

// Ambil ID berita acara dari parameter GET
$id = isset($_GET['id_ba']) ? intval($_GET['id_ba']) : 0;

// Ambil data berita acara
$sql = "SELECT * FROM berita_acara WHERE id_ba = $id";
$result = $koneksi->query($sql);

if ($result && $row = $result->fetch_assoc()) {
     // Hitung selisih tahun antara periodeakhir_ba dan periodeawal_ba
        $periode_awal = new DateTime($row['periodeawal_ba']);
        $periode_akhir = new DateTime($row['periodeakhir_ba']);
        $selisih_tahun = $periode_akhir->diff($periode_awal)->y;

        $hanyatanggal_ba = date('Y-m-d', strtotime($row['tanggal_ba']));
        $tanggalberita = tgl_indo($hanyatanggal_ba);
   // HTML content with precise spacing
   // style inline untuk menghindari masalah dengan CSS eksternal
$html = '
<style>
    .header {
        text-align: center;
        font-weight: bold;
        font-size: 12pt;
        margin-bottom: 12mm;
        text-decoration: underline;
    }
    .content {
        font-size: 11pt;
        line-height: 1.4;
    }
    .field-label {
        width: 65mm;
        display: inline-block;
        vertical-align: top;
    }
    .field-value {
        display: inline-block;
        width: 110mm;
    }
    .indent {
        margin-left: 10mm;
    }
    .signature-container {
        margin-top: 10mm;
    }
    .signature-block {
        width: 80mm;
        display: inline-block;
    }
    .right-align {
        text-align: right;
        margin-right: 15mm;
        margin-bottom: 10mm;
    }
    .footer-note {
        margin-top: 3mm;
        font-size: 9pt;
        font-style: italic;
    }
</style>

<div class="header">'.$row['judul_ba'].'</div>

<div class="content">
    <p style="text-align: justify; margin-bottom: 8mm;">
        Pada hari ini, tanggal '.$tanggalberita.' sesuai dengan Pasal 8 Peraturan Menteri Kesehatan Republik Indonesia Nomor 269 Tahun 2008 tentang Penyimpanan, Pemusnahan, dan Kerahasiaan, kami yang bertanda tangan di bawah ini :
    </p>
    
    <p style="margin-bottom: 0mm;">
        <span class="field-label">Nama Apoteker Penanggung Jawab</span>: <span class="field-value">'.$row['pj_apoteker_ba'].'</span>
    </p>
    <p style="margin-bottom: 0mm;">
        <span class="field-label">Nomor NIP</span>: <span class="field-value">'.$row['pj_nip_ba'].'</span>
    </p>
    <p style="margin-bottom: 0mm;">
        <span class="field-label">Nama Puskesmas</span>: <span class="field-value">UPTD. Puskesmas Kalivates</span>
    </p>
    <p style="margin-bottom: 8mm;">
        <span class="field-label">Alamat Puskesmas</span>: <span class="field-value">Jl. Basuki Rahmat No. 199, Tumpengesari, Tegal Besar, Kalivates, Jember</span>
    </p>
    
    <p style="margin-bottom: 0mm;">Dengan disaksikan oleh:</p>
    
    <p style="margin-bottom: 0mm;">
        <span class="field-label">Nama</span>: <span class="field-value">'.$row['saksi_ba'].'</span>
    </p>
    <p style="margin-bottom: 0mm;">
        <span class="field-label">NIP</span>: <span class="field-value">'.$row['saksi_nip_ba'].'</span>
    </p>
    <p style="margin-bottom: 8mm;">
        <span class="field-label">Jabatan</span>: <span class="field-value">'.$row['saksi_jabatan_ba'].'</span>
    </p>
    
    <p style="text-align: justify; margin-bottom: 8mm;">
        Telah melakukan pemusnahan dokumen rekam medis pada Puskesmas kami, yang telah melewati batas waktu penyimpanan selama '.$selisih_tahun.' tahun, yaitu dokumen rekam medis yang retensi dari tanggal '.tgl_indo($row['periodeawal_ba']).' - '.tgl_indo($row['periodeakhir_ba']).'.
    </p>
    
    <p style="margin-bottom: 15mm;">
        <span class="field-label">Tempat dilakukan pemusnahan</span>: <span class="field-value">UPTD. Puskesmas Kalivates (Jl. Basuki Rahmat No. 199)</span>
    </p>
    
    <p style="text-align: justify; margin-bottom: 5mm;">
        Demikianlah berita acara ini kami buat sesungguhnya dengan penuh tanggung jawab.
    </p>
    
    <p style="margin-bottom: 0mm;">Saksi - saksi :</p>
    <p style="margin-bottom: 15mm;">Kepala UPTD. Puskesmas Kalivates</p>
</div>
<div class="signature-container">
    <div class="right-align">
        Jember, '.$tanggalberita.'<br>
        Yang membuat berita acara,
    </div>
    
    <div style="margin-top: 15mm;">
        <div class="signature-block" style="float: left;">
            ('.$row['saksi_ba'].')<br>
            '.$row['saksi_nip_ba'].'
        </div>
        
        <div class="signature-block" style="float: right;">
            ('.$row['pj_apoteker_ba'].')<br>
            '.$row['pj_nip_ba'].'
        </div>
    </div>
</div>

<div style="clear: both;"></div>

<div class="footer-note">
    Dokumen ini dicetak secara elektronik, tanda tangan asli terdapat pada dokumen fisik
</div>
';
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
    $mpdf->SetTitle(''.$row['judul_ba'].'');
    $mpdf->SetAuthor('Puskesmas Kalivates');
    $mpdf->WriteHTML($html); // $mpdf->Output('berita_acara_'.$row['id_ba'].'.pdf', 'D'); 
    echo $html;// tampilkan di browser
    echo '<script>window.print();</script>';
} else {
    echo "Data tidak ditemukan.";
}
?>
