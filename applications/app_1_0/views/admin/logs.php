

<div class="container-default">
	<div class="col-lg-12">
		<?php echo $this->lib->alert_message();?>
		<hr>
		<div class="col-lg-4 bg_fff margin-r-10">
			<h3>Log file list</h3>
			<div class="well file_content">
				<ul class="list-unstyled">
				<?php 

				foreach ($files as $key=>$val) {
					if(is_dir($val)==true){
						continue;
					}

					?>
					<li data-file-name="<?php echo base64_encode($val);?>" class="margin-b-5 file_list row <?php if(isset($file_name) AND $file_name==$val){ echo "font-w-800";}?>">
						<a class="col-lg-10" href="#">
							<?php echo $val;?>
								
								<span class="pull-right"><?php echo byte_format(filesize($directory.DIRECTORY_SEPARATOR.$val));?></span>
						
						</a>
						<a class="col-lg-2" onclick="return confirm('Are you sure want to delete this log file');" href="<?php echo base_url('admin/log/del/'.base64_encode($val));?>" >
							<button class="btn btn-danger btn-icon btn-xs pull-right">
								<i class="fa fa-trash"></i>
							</button>
						</a>
					</li>
					<?php
				}
				?>
				</ul>
			</div>			
		</div>

		<div class="col-lg-7 bg_fff margin-l-10" id="file_content_div">
			<h3>Log content for file : <?php if(isset($file_name)){ echo $file_name;};?></h3>
			<div class="well file_content margin-10" id="file_content_well">
				
				<small>
				<?php 
				if(isset($content)){
					echo nl2br($content);
				}
				?>
				</small>
			</div>
		</div>
		
	</div>
	<div class="clearfix"></div>

</div>


<style type="text/css">
	.file_content{
		max-height: 600px;
		overflow-y: scroll;
	}
</style>

<script type="text/javascript">
	$('.file_list').click(function(){
		$('#file_content_div').html("<p class='text-center' style='margin:100px auto'><i class='fa fa-spinner fa-pulse fa-3x fa-fw'></i><br>Loading...</p>");
		$('.file_list').removeClass('font-w-800');
		file_name = $(this).data('file-name');
		$(this).addClass('font-w-800');
		$('#file_content_div').load('<?php echo base_url('admin/log/load_file/')?>/'+file_name);

		
	});
</script>

