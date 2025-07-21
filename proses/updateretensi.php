<?php
include 'koneksi.php';
use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

// if (isset($_POST['but_update'])) { 
//     if (isset($_POST['chk'])) { 
//         foreach ($_POST['chk'] as $updateid) { 
//             // Cek apakah elemen dengan nama 'rm_id' dan indeks sesuai ada di $_POST
//             if (isset($_POST['rm_id' . $updateid]) && $_POST['rm_id' . $updateid] != '') { 
//                 $norm = $_POST['rm_id' . $updateid];
//                 // Tampilkan nilai yang ditemukan
//                 // Uncomment query berikut jika ingin mengupdate database
//                 $updaterm = "UPDATE rm SET status='RETENSI' WHERE no_rm='$updateid'";
//                 mysqli_query($koneksi, $updaterm); 
//                 header("Location: ../halaman/petugas/rminaktif.php", true, 301);
//                 exit();
//                 // echo 'done';
//             } else {
//                 echo 'failed'; // Tampilkan pesan error jika nilai kosong
//             }
//         } 
//     } 
// }
// ---------------------------------------------- MUSNAH

if (isset($_POST['but_update'])) {
     date_default_timezone_set('Asia/Jakarta');
     $tanggalSekarang = date('Y-m-d H:i:s');
    if (!empty($_POST['chk'])) {
        $selectedIds = $_POST['chk'];

        // Ubah status rekam medis menjadi "MUSNAH"
        foreach ($selectedIds as $no_rm) {
            mysqli_query($koneksi, "UPDATE rm SET status = 'RETENSI' , tanggal_status = '$tanggalSekarang' WHERE no_rm = '$no_rm'");
        }

        // Ambil data untuk PDF
        $idList = implode("','", $selectedIds);
        $query = mysqli_query($koneksi, "SELECT a.no_rm, b.nama_pasien, b.tanggal_lahir_pasien, b.jenis_kelamin_pasien, b.alamat_pasien 
                                         FROM rm a JOIN pasien b ON a.id_pasien = b.id_pasien 
                                         WHERE a.no_rm IN ('$idList')");

        // Buat isi PDF
        $html = '<h2 style="text-align:center;">Laporan Retensi Rekam Medis</h2>';
        $html .= '<table border="1" cellpadding="8" cellspacing="0" width="100%"><tr><th>No</th><th>No RM</th><th>Nama Pasien</th><th>Tanggal Lahir</th><th>Jenis Kelamin</th><th>Alamat</th></tr>';
        
        $no = 1;
        while ($row = mysqli_fetch_array($query)) {
            $html .= "<tr>
                        <td>{$no}</td>
                        <td>{$row['no_rm']}</td>
                        <td>{$row['nama_pasien']}</td>
                        <td>{$row['tanggal_lahir_pasien']}</td>
                        <td>{$row['jenis_kelamin_pasien']}</td>
                        <td>{$row['alamat_pasien']}</td>
                      </tr>";
            $no++;
        }
        $html .= '</table>';

        // Generate PDF dengan Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Simpan file PDF
        $pdfOutput = $dompdf->output();
        $filename = "retensi_rekam_medis_" . date("YmdHis") . ".pdf";
        $pdfPath = "pdf_reports/" . $filename;
        file_put_contents($pdfPath, $pdfOutput);

        // Cek apakah file PDF telah disimpan
        if (!file_exists($pdfPath)) {
            echo "<script>alert('Gagal menyimpan file PDF.'); window.location.href='../halaman/petugas/rminaktif.php';</script>";
            exit;
        }

        // Ambil email dari tabel user
        $userEmails = [];
        $emailQuery = mysqli_query($koneksi, "SELECT * FROM pengguna");

        while ($emailRow = mysqli_fetch_assoc($emailQuery)) {
            $userEmails[] = $emailRow['email_pengguna'];
        }

        if (!empty($userEmails)) {
            // Konfigurasi PHPMailer
            $mail = new PHPMailer(true);
            try {
                // Konfigurasi SMTP
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com'; // Sesuaikan dengan SMTP server
                $mail->SMTPAuth   = true;
                $mail->Username   = ''; // Ganti dengan email pengirim
                $mail->Password   = ''; // Ganti dengan password email
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                // Pengirim
                $mail->setFrom('no-reply@mediafarmalaboratories.com', 'Admin Rekam Medis');

                // Tambahkan penerima (semua user yang diambil dari database)
                foreach ($userEmails as $email) {
                    $mail->addAddress($email);
                }

                // Tambahkan lampiran PDF
                $mail->addAttachment($pdfPath);

                // Konten Email
                $mail->isHTML(true);
                $mail->Subject = 'Laporan Retensi Rekam Medis';
                $mail->Body    = '<p>Berikut adalah laporan retensi rekam medis dalam bentuk PDF.</p>';

                // Kirim email
                $mail->send();

                // Setelah email terkirim, lakukan redirect
                echo "<script>alert('Data berhasil Di retensi dan laporan dikirim ke email.'); window.location.href='../halaman/petugas/rminaktif.php';</script>";
            } catch (Exception $e) {
                echo "<script>alert('Gagal mengirim email: {$mail->ErrorInfo}'); window.location.href='../halaman/petugas/rminaktif.php';</script>";
            }
        } else {
            echo "<script>alert('Tidak ada email penerima yang ditemukan.'); window.location.href='../halaman/petugas/rminaktif.php';</script>";
        }
    } else {
        echo "<script>alert('Pilih data terlebih dahulu!'); window.location.href='../halaman/petugas/rminaktif.php';</script>";
    }
}

 // Sesuaikan path dengan lokasi autoload.php dari Composer

 if (isset($_POST['but_musnah'])) {
    date_default_timezone_set('Asia/Jakarta');
    $tanggalSekarang = date('Y-m-d H:i:s');
    if (!empty($_POST['chk'])) {
        $selectedIds = $_POST['chk'];

        // Ubah status rekam medis menjadi "MUSNAH"
        foreach ($selectedIds as $no_rm) {
            mysqli_query($koneksi, "UPDATE rm SET status = 'MUSNAH', tanggal_status = '$tanggalSekarang' WHERE no_rm = '$no_rm'");
        }

        // Ambil data untuk PDF
        $idList = implode("','", $selectedIds);
        $query = mysqli_query($koneksi, "SELECT a.no_rm, b.nama_pasien, b.tanggal_lahir_pasien, b.jenis_kelamin_pasien, b.alamat_pasien 
                                         FROM rm a JOIN pasien b ON a.id_pasien = b.id_pasien 
                                         WHERE a.no_rm IN ('$idList')");

        // Buat isi PDF
        $html = '<h2 style="text-align:center;">Laporan Pemusnahan Rekam Medis</h2>';
        $html .= '<table border="1" cellpadding="8" cellspacing="0" width="100%"><tr><th>No</th><th>No RM</th><th>Nama Pasien</th><th>Tanggal Lahir</th><th>Jenis Kelamin</th><th>Alamat</th></tr>';
        
        $no = 1;
        while ($row = mysqli_fetch_array($query)) {
            $html .= "<tr>
                        <td>{$no}</td>
                        <td>{$row['no_rm']}</td>
                        <td>{$row['nama_pasien']}</td>
                        <td>{$row['tanggal_lahir_pasien']}</td>
                        <td>{$row['jenis_kelamin_pasien']}</td>
                        <td>{$row['alamat_pasien']}</td>
                      </tr>";
            $no++;
        }
        $html .= '</table>';

        // Generate PDF dengan Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Simpan file PDF
        $pdfOutput = $dompdf->output();
        $filename = "pemusnahan_rekam_medis_" . date("YmdHis") . ".pdf";
        $pdfPath = "pdf_reports/" . $filename;
        file_put_contents($pdfPath, $pdfOutput);

        // Cek apakah file PDF telah disimpan
        if (!file_exists($pdfPath)) {
            echo "<script>alert('Gagal menyimpan file PDF.'); window.location.href='../halaman/petugas/rminaktif.php';</script>";
            exit;
        }

        // Ambil email dari tabel user
        $userEmails = [];
        $emailQuery = mysqli_query($koneksi, "SELECT * FROM pengguna");

        while ($emailRow = mysqli_fetch_assoc($emailQuery)) {
            $userEmails[] = $emailRow['email_pengguna'];
        }

        if (!empty($userEmails)) {
            // Konfigurasi PHPMailer
            $mail = new PHPMailer(true);
            try {
                // Konfigurasi SMTP
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com'; // Sesuaikan dengan SMTP server
                $mail->SMTPAuth   = true;
                $mail->Username   = ''; // Ganti dengan email pengirim
                $mail->Password   = ''; // Ganti dengan password email
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                // Pengirim
                $mail->setFrom('no-reply@mediafarmalaboratories.com', 'Admin Rekam Medis');

                // Tambahkan penerima (semua user yang diambil dari database)
                foreach ($userEmails as $email) {
                    $mail->addAddress($email);
                }

                // Tambahkan lampiran PDF
                $mail->addAttachment($pdfPath);

                // Konten Email
                $mail->isHTML(true);
                $mail->Subject = 'Laporan Pemusnahan Rekam Medis';
                $mail->Body    = '<p>Berikut adalah laporan pemusnahan rekam medis dalam bentuk PDF.</p>';

                // Kirim email
                $mail->send();

                // Setelah email terkirim, lakukan redirect
                echo "<script>alert('Data berhasil dimusnahkan dan laporan dikirim ke email.'); window.location.href='../halaman/petugas/rminaktif.php';</script>";
            } catch (Exception $e) {
                echo "<script>alert('Gagal mengirim email: {$mail->ErrorInfo}'); window.location.href='../halaman/petugas/rminaktif.php';</script>";
            }
        } else {
            echo "<script>alert('Tidak ada email penerima yang ditemukan.'); window.location.href='../halaman/petugas/rminaktif.php';</script>";
        }
    } else {
        echo "<script>alert('Pilih data terlebih dahulu!'); window.location.href='../halaman/petugas/rminaktif.php';</script>";
    }
}

?>
