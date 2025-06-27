<?php include 'template/header.php'; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Rekam Medis Retensi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="home.php">Beranda</a></li>
                        <li class="breadcrumb-item active">Rekam Medis Retensi</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row"></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Rekam Medis Retensi</h3>
                        </div>

                        <div class="card-body">
                            <form action="../../proses/updateretensi.php" method="post">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><input type='checkbox' id='checkAll'> Check All</th>
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
                                    <tbody>
                                        <?php 
                                        include '../../proses/koneksi.php';
                                        include '../../proses/tanggalindo.php';

                                        $no = 1;
                                        $data = mysqli_query($koneksi, "SELECT DISTINCT a.id_pasien, a.nama_pasien, a.tanggal_lahir_pasien, a.jenis_kelamin_pasien, a.alamat_pasien, b.id_pasien, c.status, c.no_rm 
                                            FROM pasien a 
                                            JOIN kunjungan b ON a.id_pasien = b.id_pasien 
                                            JOIN rm c ON a.id_pasien = c.id_pasien 
                                            WHERE b.tanggal_kunjungan < DATE_SUB(CURDATE(), INTERVAL 2 YEAR) AND c.status = 'RETENSI' 
                                            ORDER BY b.tanggal_kunjungan DESC;");
            
                                        while ($d = mysqli_fetch_array($data)) {
                                            ?>
                                            <tr>
                                                <td><input type='checkbox' class='mycheckbox' name='chk[]' value='<?= $d['no_rm'] ?>'></td>
                                                <td><?php echo $no++; ?></td>
                                                <td><?php echo $d['no_rm']; ?></td>
                                                <td><?php echo $d['nama_pasien']; ?></td>
                                                <td><?php echo tgl_indo(date($d['tanggal_lahir_pasien'])); ?></td>
                                                <td><?php echo $d['jenis_kelamin_pasien']; ?></td>
                                                <td><?php echo $d['alamat_pasien']; ?></td>
                                                <?php 
                                                $idpasien = $d['id_pasien'];
                                                $kunjungan = mysqli_query($koneksi, "select MAX(tanggal_kunjungan) as knj from kunjungan where id_pasien = '$idpasien'");
                                                while ($tampilkunjungan = mysqli_fetch_array($kunjungan)) { ?>
                                                    <td> <a href="#" data-toggle="modal" data-target="#edit<?php echo $idpasien ?>"> <?php echo tgl_indo(date($tampilkunjungan['knj'])); }?></a></td>
                                                <td><span class="right badge badge-warning">Saved / Retensi</span></td>
                                            </tr>
                                            <?php 
                                            include '../../proses/showkunjungan.php';
                                        } 
                                        ?>
                                    </tbody>
                                </table>
                                <p class="mt-2">Total Data Dipilih: <span id="totalChecked">0</span></p>
                                <input type='submit' class='btn btn-danger' value='MUSNAHKAN' name='but_musnah'><br><br>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let checkboxes = document.querySelectorAll(".mycheckbox");
    let checkAll = document.getElementById("checkAll");
    let totalChecked = document.getElementById("totalChecked");

    // Load saved checkbox states
    let checkedItems = JSON.parse(localStorage.getItem("checkedItems")) || [];

    checkboxes.forEach(checkbox => {
        if (checkedItems.includes(checkbox.value)) {
            checkbox.checked = true;
        }
    });

    // Update total count
    function updateTotal() {
        let checkedBoxes = document.querySelectorAll(".mycheckbox:checked");
        totalChecked.textContent = checkedBoxes.length;

        // Save checked values to localStorage
        let selectedValues = Array.from(checkedBoxes).map(cb => cb.value);
        localStorage.setItem("checkedItems", JSON.stringify(selectedValues));
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener("change", updateTotal);
    });

    // Check all functionality
    checkAll.addEventListener("change", function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = checkAll.checked;
        });
        updateTotal();
    });

    updateTotal(); // Ensure correct count on page load
});
</script>

<?php include 'template/footer.php'; ?>
