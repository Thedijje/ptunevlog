

<div class="container-default">
	<div class="col-lg-12">
		<?php echo $this->lib->alert_message();?>
		<div class="panel panel-default col-lg-12">
			<form class="form" action="<?php echo base_url('admin/pages/add')?>" enctype="multipart/form-data" method="post">
				<h3>Add Page</h3>
				<div class='col-lg-8'>
				<div class="form-group">
						<label>Title</label>
						<input type="text" name="page_title" class="form-control" placeholder="Enter Page Title" value="">
					</div>
					
					<div class="form-group">
						<label>URL</label>
						<input type="text" class="form-control" name="page_url" placeholder="Enter your-page-url" value="" aria-describedby="basic-addon1">
						
					</div>
					
					<div class="form-group">
					<label>Content</label>
					<textarea name="page_content" id="editor_kama" class="form-control" rows="20"></textarea>
					</div>
				
				</div>
				
						<div class='col-lg-4'>
						<div class="form-group">
							<label>Meta keyword</label>
							<textarea name="page_meta_keyword" id="tokenfield-typeahead" class="form-control" placeholder="Enter keywords separated by comma"></textarea>
						</div>
						
						<div class="form-group">
							<label>Meta Description</label>
							<textarea name="page_meta_description" class="form-control" placeholder="Enter Meta Description" ></textarea>
						</div>
						
						
						
						<div class="form-group">
							<button class="btn btn-primary btn-block"><i class="fa fa-save"></i> Save Page</button>
						</div>
					   
						
						
					</div>
				
									
			</form>
		</div>
		
	</div>
	<div class="clearfix"></div>

</div>



