<?php
include 'koneksi.php';
use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

// Mulai session
session_start();

if (isset($_POST['but_update'])) {
    date_default_timezone_set('Asia/Jakarta');
    $tanggalSekarang = date('Y-m-d H:i:s');
    
    if (!empty($_POST['chk'])) {
        $selectedIds = $_POST['chk'];
        
        // Ambil data petugas dari session
        if (isset($_SESSION['nama_pengguna'])) {
            $petugasId = $_SESSION['idpengguna'];
            $namaPetugas = $_SESSION['nama_pengguna'];
        } else {
            echo "<script>alert('Session petugas tidak ditemukan. Silakan login kembali.'); window.location.href='../halaman/petugas/rminaktif.php';</script>";
            exit;
        }

        // Ubah status rekam medis menjadi "RETENSI"
        foreach ($selectedIds as $no_rm) {
            mysqli_query($koneksi, "UPDATE rm SET status = 'RETENSI', tanggal_status = '$tanggalSekarang' WHERE no_rm = '$no_rm'");
        }

        // Ambil data untuk PDF
        $idList = implode("','", $selectedIds);
        $query = mysqli_query($koneksi, "SELECT a.no_rm, b.nama_pasien, b.tanggal_lahir_pasien, b.jenis_kelamin_pasien, b.alamat_pasien 
                                         FROM rm a JOIN pasien b ON a.id_pasien = b.id_pasien 
                                         WHERE a.no_rm IN ('$idList')");

        // Buat isi PDF dengan kop surat
        $html = '
        <style>
            .header {
                border-bottom: 2px solid #000;
                padding-bottom: 10px;
                margin-bottom: 20px;
            }
            .header-table {
                width: 100%;
                border-collapse: collapse;
            }
            .logo {
                width: 80px;
                height: 80px;
            }
            .instansi-info {
                text-align: center;
                vertical-align: top;
            }
            .instansi-name {
                font-size: 16px;
                font-weight: bold;
                margin: 0;
            }
            .instansi-address {
                font-size: 12px;
                margin: 2px 0;
            }
            .title {
                text-align: center;
                margin: 20px 0;
                font-size: 18px;
                font-weight: bold;
            }
            .data-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            .data-table th, .data-table td {
                border: 1px solid #000;
                padding: 8px;
                text-align: left;
            }
            .data-table th {
                background-color: #f2f2f2;
            }
            .footer {
                margin-top: 30px;
                text-align: right;
                font-size: 12px;
            }
            .info-box {
                margin: 15px 0;
                padding: 10px;
                background-color: #f9f9f9;
                border-left: 4px solid #007bff;
            }
            .ttd-section {
                margin-top: 50px;
                text-align: right;
            }
            .ttd-box {
                display: inline-block;
                text-align: center;
            }
        </style>

        <div class="header">
            <table class="header-table">
                <tr>
                    <td style="width: 15%; text-align: center;">
                        <!-- Ganti dengan path logo instansi Anda -->
                       
                    </td>
                    <td class="instansi-info" style="width: 70%;">
                        <p class="instansi-name">PUSKESMAS KALIWATES</p>
                        <p class="instansi-address">Jl. Basuki Rahmat No.199, Tumpengsari, Tegal Besar, Kec. Kaliwates, Kabupaten Jember, Jawa Timur 68131</p>
                        <p class="instansi-address">Telepon: (0331) 321301</p>
                    </td>
                    <td style="width: 15%; text-align: center;">
                        <!-- Optional: tambahan logo atau kode lainnya -->
                    </td>
                </tr>
            </table>
        </div>

        <div class="title">LAPORAN RETENSI REKAM MEDIS</div>
        
        <div class="info-box">
            <p><strong>Tanggal Retensi:</strong> ' . date('d-m-Y H:i:s') . '</p>
            <p><strong>Petugas Penanggung Jawab:</strong> ' . $namaPetugas . '</p>
            <p><strong>Jumlah Rekam Medis:</strong> ' . count($selectedIds) . ' berkas</p>
        </div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No RM</th>
                    <th>Nama Pasien</th>
                    <th>Tanggal Lahir</th>
                    <th>Jenis Kelamin</th>
                    <th>Alamat</th>
                </tr>
            </thead>
            <tbody>';
        
        $no = 1;
        while ($row = mysqli_fetch_array($query)) {
            // Format tanggal lahir
            $tanggalLahir = date('d-m-Y', strtotime($row['tanggal_lahir_pasien']));
            
            $html .= "<tr>
                        <td>{$no}</td>
                        <td>{$row['no_rm']}</td>
                        <td>{$row['nama_pasien']}</td>
                        <td>{$tanggalLahir}</td>
                        <td>{$row['jenis_kelamin_pasien']}</td>
                        <td>{$row['alamat_pasien']}</td>
                      </tr>";
            $no++;
        }
        
        $html .= '</tbody>
        </table>
        
        <div class="ttd-section">
            <div class="ttd-box">
                <p>Jember, ' . date('d-m-Y') . '</p>
                <p>Petugas Retensi,</p>
                <br><br><br>
                <p><strong>' . $namaPetugas . '</strong></p>
            </div>
        </div>';

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
        
        // Pastikan folder pdf_reports ada
        if (!is_dir('pdf_reports')) {
            mkdir('pdf_reports', 0777, true);
        }
        
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
                $mail->Subject = 'Laporan Retensi Rekam Medis - ' . date('d-m-Y');
                $mail->Body    = '<p>Berikut adalah laporan retensi rekam medis yang telah dilakukan pada ' . date('d-m-Y H:i:s') . '.</p>
                                 <p><strong>Petugas Penanggung Jawab:</strong> ' . $namaPetugas . '</p>
                                 <p><strong>Jumlah Berkas:</strong> ' . count($selectedIds) . ' rekam medis</p>';

                // Kirim email
                $mail->send();

                // Setelah email terkirim, lakukan redirect
                echo "<script>alert('Data berhasil di retensi dan laporan dikirim ke email.'); window.location.href='../halaman/petugas/rminaktif.php';</script>";
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

if (isset($_POST['but_musnah'])) {
    date_default_timezone_set('Asia/Jakarta');
    $tanggalSekarang = date('Y-m-d H:i:s');
    
    if (!empty($_POST['chk'])) {
        $selectedIds = $_POST['chk'];
        
        // Ambil data petugas dari session
        if (isset($_SESSION['id_pengguna']) && isset($_SESSION['nama_pengguna'])) {
            $petugasId = $_SESSION['idpengguna'];
            $namaPetugas = $_SESSION['nama_pengguna'];
        } else {
            echo "<script>alert('Session petugas tidak ditemukan. Silakan login kembali.'); window.location.href='../halaman/petugas/rminaktif.php';</script>";
            exit;
        }

        // Ubah status rekam medis menjadi "MUSNAH"
        foreach ($selectedIds as $no_rm) {
            mysqli_query($koneksi, "UPDATE rm SET status = 'MUSNAH', tanggal_status = '$tanggalSekarang' WHERE no_rm = '$no_rm'");
        }

        // Ambil data untuk PDF
        $idList = implode("','", $selectedIds);
        $query = mysqli_query($koneksi, "SELECT a.no_rm, b.nama_pasien, b.tanggal_lahir_pasien, b.jenis_kelamin_pasien, b.alamat_pasien 
                                         FROM rm a JOIN pasien b ON a.id_pasien = b.id_pasien 
                                         WHERE a.no_rm IN ('$idList')");

        // Buat isi PDF dengan kop surat
        $html = '
        <style>
            .header {
                border-bottom: 2px solid #000;
                padding-bottom: 10px;
                margin-bottom: 20px;
            }
            .header-table {
                width: 100%;
                border-collapse: collapse;
            }
            .logo {
                width: 80px;
                height: 80px;
            }
            .instansi-info {
                text-align: center;
                vertical-align: top;
            }
            .instansi-name {
                font-size: 16px;
                font-weight: bold;
                margin: 0;
            }
            .instansi-address {
                font-size: 12px;
                margin: 2px 0;
            }
            .title {
                text-align: center;
                margin: 20px 0;
                font-size: 18px;
                font-weight: bold;
            }
            .data-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            .data-table th, .data-table td {
                border: 1px solid #000;
                padding: 8px;
                text-align: left;
            }
            .data-table th {
                background-color: #f2f2f2;
            }
            .footer {
                margin-top: 30px;
                text-align: right;
                font-size: 12px;
            }
            .info-box {
                margin: 15px 0;
                padding: 10px;
                background-color: #f9f9f9;
                border-left: 4px solid #dc3545;
            }
            .ttd-section {
                margin-top: 50px;
                text-align: right;
            }
            .ttd-box {
                display: inline-block;
                text-align: center;
            }
        </style>

        <div class="header">
            <table class="header-table">
                <tr>
                    <td style="width: 15%; text-align: center;">
                        <!-- Ganti dengan path logo instansi Anda -->
                        
                    </td>
                    <td class="instansi-info" style="width: 70%;">
                        <p class="instansi-name">PUSKESMAS KALIWATES</p>
                        <p class="instansi-address">Jl. Basuki Rahmat No.199, Tumpengsari, Tegal Besar, Kec. Kaliwates, Kabupaten Jember, Jawa Timur 68131</p>
                        <p class="instansi-address">Telepon: (0331) 321301</p>
                    </td>
                    <td style="width: 15%; text-align: center;">
                        <!-- Optional: tambahan logo atau kode lainnya -->
                    </td>
                </tr>
            </table>
        </div>

        <div class="title">LAPORAN PEMUSNAHAN REKAM MEDIS</div>
        
        <div class="info-box">
            <p><strong>Tanggal Pemusnahan:</strong> ' . date('d-m-Y H:i:s') . '</p>
            <p><strong>Petugas Penanggung Jawab:</strong> ' . $namaPetugas . '</p>
            <p><strong>Jumlah Rekam Medis:</strong> ' . count($selectedIds) . ' berkas</p>
        </div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No RM</th>
                    <th>Nama Pasien</th>
                    <th>Tanggal Lahir</th>
                    <th>Jenis Kelamin</th>
                    <th>Alamat</th>
                </tr>
            </thead>
            <tbody>';
        
        $no = 1;
        while ($row = mysqli_fetch_array($query)) {
            // Format tanggal lahir
            $tanggalLahir = date('d-m-Y', strtotime($row['tanggal_lahir_pasien']));
            
            $html .= "<tr>
                        <td>{$no}</td>
                        <td>{$row['no_rm']}</td>
                        <td>{$row['nama_pasien']}</td>
                        <td>{$tanggalLahir}</td>
                        <td>{$row['jenis_kelamin_pasien']}</td>
                        <td>{$row['alamat_pasien']}</td>
                      </tr>";
            $no++;
        }
        
        $html .= '</tbody>
        </table>
        
        <div class="ttd-section">
            <div class="ttd-box">
                <p>Jember, ' . date('d-m-Y') . '</p>
                <p>Petugas Pemusnahan,</p>
                <br><br><br>
                <p><strong>' . $namaPetugas . '</strong></p>
            </div>
        </div>';

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
        
        // Pastikan folder pdf_reports ada
        if (!is_dir('pdf_reports')) {
            mkdir('pdf_reports', 0777, true);
        }
        
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
                $mail->Subject = 'Laporan Pemusnahan Rekam Medis - ' . date('d-m-Y');
                $mail->Body    = '<p>Berikut adalah laporan pemusnahan rekam medis yang telah dilakukan pada ' . date('d-m-Y H:i:s') . '.</p>
                                 <p><strong>Petugas Penanggung Jawab:</strong> ' . $namaPetugas . '</p>
                                 <p><strong>Jumlah Berkas:</strong> ' . count($selectedIds) . ' rekam medis</p>';

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