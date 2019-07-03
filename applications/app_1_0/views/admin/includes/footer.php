<!-- Start Footer -->
<div class="row footer">
  <div class="col-md-6 text-left">
  Copyright &copy; <?php echo date('y',time());?> <a href="#" target="_blank"><?php echo $config['sitename']?></a> All rights reserved.
  </div>
  <div class="col-md-6 text-right">
    Design and Developed by <a href="<?php echo PROJECT_DEFAULT_URL ?>" target="_blank">SeekGeeks</a>
  </div> 
</div>
<script src="//cdn.ckeditor.com/4.8.0/standard/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo base_url('static/admin/'.APP_V.'/js/jquery.min.js')?>"></script>
<script src="<?php echo base_url('static/admin/'.APP_V.'/js/bootstrap/bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('static/admin/'.APP_V.'/js/date-range-picker/daterangepicker.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('static/admin/'.APP_V.'/js/plugins.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('static/admin/'.APP_V.'/js/redcurrey_admin.js')?>"></script>
	<script type="text/javascript">
$(document).ready(function() {
  $('#date-picker').daterangepicker({ singleDatePicker: true }, function(start, end, label) {
    console.log(start.toISOString(), end.toISOString(), label);
  });
});
</script>
<script>
	function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
	</script>


 <script>
                // Replace the <textarea id="editor1"> with a CKEditor
                // instance, using default configuration.
                CKEDITOR.replace( 'editor1' );
            </script>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel" align="center">Action</h4>
      </div>
      <div class="modal-body" id="loadData">
        <h4 align="center"><i class="fa fa-spinner fa-pulse"></i></h4>
        <h4 align="center">Please wait...</h4>
      </div>
    </div>
  </div>
</div>

</body>

</html>