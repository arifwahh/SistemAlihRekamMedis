 <!-- /.content-wrapper -->
 <footer class="main-footer">
    <strong>Copyright &copy; 2024 <a href="#">Arif Wahyudi</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../../../assets/administrator/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../../../assets/administrator/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../../assets/administrator/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="../../../assets/administrator/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="../../../assets/administrator/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="../../../assets/administrator/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="../../../assets/administrator/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="../../../assets/administrator/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="../../../assets/administrator/plugins/moment/moment.min.js"></script>
<script src="../../../assets/administrator/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../../../assets/administrator/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="../../../assets/administrator/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="../../../assets/administrator/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../../../assets/administrator/dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../../assets/administrator/dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="../../../assets/administrator/dist/js/pages/dashboard.js"></script>
<!-- DataTables  & Plugins -->
<script src="../../../assets/administrator/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../../assets/administrator/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../../assets/administrator/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../../assets/administrator/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../../../assets/administrator/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../../../assets/administrator/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../../../assets/administrator/plugins/jszip/jszip.min.js"></script>
<script src="../../../assets/administrator/plugins/pdfmake/pdfmake.min.js"></script>
<script src="../../../assets/administrator/plugins/pdfmake/vfs_fonts.js"></script>
<script src="../../../assets/administrator/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../../../assets/administrator/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../../../assets/administrator/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- Javascript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="../../../assets/js/wizard.js"></script>
<!-- Page specific script -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
<script>  
$(document).ready(function(){  
     var i=1;  
     $('#add').click(function(){  
          i++;  
          $('#dynamic_field').append('<tr id="row'+i+'"> <td class="col-md-1"><input type="date" name="tanggalkunjungan[]" class="form-control nilai_list" placeholder="Tanggal Kunjungan" /></td><td class="col-md-3"><input type="text" name="keluhankunjungan[]" class="form-control nilai_list" placeholder="Keluhan" /></td><td class="col-md-1"><input type="text" name="polikunjungan[]" class="form-control nilai_list" placeholder="Poli" /></td><td class="col-md-2"><input type="text" name="klinikkunjungan[]" class="form-control nilai_list" placeholder="Klinik" /></td><td class="col-md-2"><select name="biaya[]" class="form-control"> Biaya<option value="BPJS" selected>BPJS</option><option value="Umum">Umum</option></select></td><td class="col-md-3"><input type="text" name="nobpjs[]" class="form-control nilai_list" placeholder="No BPJS (Kosongi jika Umum)" /></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
     });  
     $(document).on('click', '.btn_remove', function(){  
          var button_id = $(this).attr("id");   
          $('#row'+button_id+'').remove();  
     });  
     $('#submit').click(function(){            
          $.ajax({  
               url:"../../../proses/tambahalldata.php",  
               method:"POST",  
               data:$('#add_keluhankunjungan').serialize(),  
               success:function(data)  
               {  
                    alert(data);  
                    $('#add_keluhankunjungan')[0].reset();  
               }  
          });  
     });  
});  
</script>
</body>
</html>
