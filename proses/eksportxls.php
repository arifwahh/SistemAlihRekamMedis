<?php
include('koneksi.php');
require '../vendor/autoload.php';
 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
 
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
 
$sheet->setCellValue('A1', 'NO');
$sheet->setCellValue('B1', 'NIK');
$sheet->setCellValue('C1', 'NAMA PASIEN');
$sheet->setCellValue('D1', 'NAMA KK PASIEN');
$sheet->setCellValue('E1', 'JENIS KELAMIN');
$sheet->setCellValue('F1', 'PEKERJAAN');
$sheet->setCellValue('G1', 'TANGGAL LAHIR');
$sheet->setCellValue('H1', 'AGAMA');
$sheet->setCellValue('I1', 'ALAMAT');
 
$data = mysqli_query($koneksi,"select * from pasien");
$i = 2;
$no = 1;
while($d = mysqli_fetch_array($data))
{
    $sheet->setCellValue('A'.$i, $no++);
    $sheet->setCellValue('B'.$i, $d['nik_pasien']);
    $sheet->setCellValue('C'.$i, $d['nama_pasien']);
    $sheet->setCellValue('D'.$i, $d['nama_kk_pasien']);
    $sheet->setCellValue('E'.$i, $d['jenis_kelamin_pasien']);
    $sheet->setCellValue('F'.$i, $d['pekerjaan_pasien']);    
    $sheet->setCellValue('G'.$i, $d['tanggal_lahir_pasien']);    
    $sheet->setCellValue('H'.$i, $d['agama_pasien']);
    $sheet->setCellValue('i'.$i, $d['alamat_pasien']);    
    $i++;
}
$writer = new Xlsx($spreadsheet);
$writer->save('Pasien.xlsx');
$file_url = 'https://dwipayacitramandiri.com/proses/Pasien.xlsx';  
header('Content-Type: application/octet-stream');  
header("Content-Transfer-Encoding: utf-8");   
header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\"");   
readfile($file_url);
unlink($file_url);
?>