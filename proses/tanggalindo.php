<?php
function tgl_indo($tanggalJam){
	$bulan = array (
		1 =>   'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
	);
	// Pisahkan tanggal dan jam
	$parts = explode(' ', $tanggalJam);
	$tanggal = $parts[0];
	$jam = isset($parts[1]) ? $parts[1] : '';

	$pecahkan = explode('-', $tanggal);

	if (count($pecahkan) === 3 && is_numeric($pecahkan[1]) && isset($bulan[(int)$pecahkan[1]])) {
		$hasil = $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
		if ($jam != '') {
			$hasil .= ' ' . $jam;
		}
		return $hasil;
	} else {
		return $tanggalJam; // return original if format is invalid
	}
}
?>