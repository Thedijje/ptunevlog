

<div class="container-default">
	<div class="col-lg-12">
		<?php echo $this->lib->alert_message();?>
		
		<div class="col-lg-12 panel panel-default">
		<a href='<?php echo base_url()."admin/pages/add_new"?>'><button class="btn btn-primary btn-icon"><i class="fa fa-plus"></i> Add New Page</button></a>
			<h3>All Pages list</h3>
			<?php 
			if(isset($pages)){
			?>	
			<table class="table table-hover">
				<thead>
					<tr>
						<th>Sr</th>
						<th>Title</th>
						<th>URL</th>
						
						<th>Action</th>
					</tr>
					</thead>
					<tbody>
					<?php 
					foreach($pages as $cat){
					?>
					<tr>
						<td>#</td>
						<td><?php echo $cat->page_title;?></td>
						<td><a href="<?php echo base_url($cat->page_url)?>" target="_blank"><i class="fa fa-external-link"></i> <?php echo $cat->page_url;?></a></td>
						
						
						<td>
							
							
							<a href='<?php echo base_url('admin/pages/edit/'.base64_encode($cat->page_id))?>'><button class="btn btn-info btn-sm btn-icon"><i class="fa fa-edit"></i></button></a>
							<a onclick="return confirm('Are you sure want to delete this item permanently?')" href="<?php echo base_url('admin/pages/del/'.base64_encode($cat->page_id))?>"><button class="btn btn-danger btn-sm btn-icon"><i class="fa fa-trash"></i></button></a>
						</td>
					</tr>
					<?php
					}	
					?>
				</tbody>
			
			</table>
			<?php
			}else{
			echo 'No pages Yet';
			}
			?>
		</div>
		
	</div>
	<div class="clearfix"></div>

</div>



