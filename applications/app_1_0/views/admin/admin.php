

<div class="container-default">
	<div class="col-lg-12">
		<?php $this->lib->alert_message();?>
		<div class="clearfix"></div>
		<div class="col-lg-4">
			<div class="panel panel-default col-lg-12">
				<h3>Add New Admin</h3>
				<form class="form" action="<?php echo base_url('admin/admins/save')?>" method="post">
					<div class="form-group">
						<label>Name</label>
						<input type="text" class="form-control" name="name" required placeholder="Enter Name" value="<?php echo set_value('name');?>">
					</div>
					
					<div class="form-group">
						<label>Email</label>
						<input type="email" class="form-control" name="email" required placeholder="Enter Admin Email" value="<?php echo set_value('email');?>">
					</div>
					
					<div class="form-group">
						<label>Password</label>
						<input type="password" class="form-control" name="password" required placeholder="Enter Password" >
					</div>
					<div class="form-group">
						<label>Password</label>
						<input type="password" class="form-control" name="confirm_password" required placeholder="Enter Confirm Password" >
					</div>
					
					<div class="form-group">
						<label>Role</label>
						<select name="role" id="role" class="form-control" required>
							<option disabled selected="">Select</option>
							<?php foreach($roles as $role){
								?>
								<option value="<?=base64_encode($role->id)?>"><?=$role->role_name?></option>
								<?php
							}?>
						</select>
					</div>
					
					<div class="form-group">
						<button class="btn btn-primary btn-icon"><i class="fa fa-save"></i> Save Admin</button>
					</div>
					
				</form>
			
			</div>
		</div>
		<div class="col-lg-8" >
			<div class="panel panel-default col-lg-12" >
				<h4>List of available Admins</h4>
				<hr>
				<table class="table table-hover table-responsive">
					<thead>
						<tr>
							<th>Sr No</th>
							<th>Name</th>
							<th>Email</th>
							<th>Last login</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						if($admins){
						$i=1;
							foreach($admins as $sd){
							?>
							<tr>
								<td><?php echo $i;?></td>
								<td><?php echo $sd->name;?></td>
								<td><?php echo $sd->email;?></td>
								<td><?php if($sd->last_login){ echo timespan($sd->last_login,time(),2);?> ago <?php }else{ echo "Not yet";}?></td>
								<td>
									<button class="btn btn-icon btn-sm btn-info" onclick="$('#loadData').load('<?php echo base_url('admin/admins/edit/'.$sd->id)?>');" data-toggle="modal" data-target="#myModal"><i class="fa fa-edit"></i></button>
								
									<a onclick="return confirm('Are you sure want to trash this record?')" href="<?php echo base_url('admin/admins/del/'.$sd->id)?>"><button class="btn btn-icon btn-sm btn-danger"><i class="fa fa-trash"></i></button></a>
								</td>
							</tr>
							<?php
							$i++;
							}
							
						}else{
						?>
						<tr>
							<td colspan="4"><i class='fa fa-inbox'></i> No active Record at moment</td>
						</tr>
						<?php
						}	
						
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
