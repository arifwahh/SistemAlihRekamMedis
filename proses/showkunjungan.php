<div>
                <!-- modal daftar -->
                <div class="modal fade" id="edit<?php echo $idpasien?>">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                <?php
                  include 'koneksi.php';
                  $ambilnamapasien = mysqli_query($koneksi, "select * from pasien");
                  $namapasien = mysqli_fetch_array($ambilnamapasien); ?>
                                <h4 class="modal-title">Data Kunjungan <?= $namapasien['nama_pasien'] ?></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" style="text-align: center;">
                                <div class="container">
                                    <div class="row">
                                                                    <div class="col-md-3">
                                                                    <strong>Tanggal Kunjungan</strong>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                     <strong>Keluhan</strong>
                                                                    </div> 
                                                                    <div class="col-md-1">
                                                                     <strong>Poli</strong>
                                                                    </div>
                                                                    <div class="col-md-1">
                                                                     <strong>Biaya</strong>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                     <strong>No BPJS</strong>
                                                                    </div>
                                                                    <strong>------------------------------------------------------------------------------------------------------------------------------------------------</strong>
                                    </div>
                                <?php 
                  $ambilkunjungan = mysqli_query($koneksi, "select * from kunjungan where id_pasien = '$idpasien'");
                  while ($kunjungan = mysqli_fetch_array($ambilkunjungan)) { ?>
                                    <div class="row">
                                                                    <div class="col-md-3">
                                                                    <?php echo tgl_indo(date($kunjungan['tanggal_kunjungan'])); ?>
                                                                    </div> 
                                                                    <div class="col-md-2">
                                                                    <?php echo $kunjungan['keluhan_kunjungan']; ?>
                                                                    </div> 
                                                                    <div class="col-md-1">
                                                                    <?php echo $kunjungan['poli_kunjungan']; ?>
                                                                    </div> 
                                                                    <div class="col-md-1">
                                                                    <?php echo $kunjungan['biaya_kunjungan']; ?>
                                                                    </div> 
                                                                    <div class="col-md-3">
                                                                    <?php echo $kunjungan['no_bpjs_kunjungan']; ?>
                                                                    </div> 
                                                                </div>
                                                                <a>-------------------------------------------------------------------------------------------------------------------------------------------------------</a>
                                                            </div>
                                                            <?php } ?>
                           
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <?php 
                                 $ambilnorm = mysqli_query($koneksi, "select * from rm where id_pasien = '$idpasien'");
                                 $rm = mysqli_fetch_array($ambilnorm);
                                ?>
                                <a>No Rekam Medis : <?= $rm['no_rm'] ?> </a>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- end modal daftar -->