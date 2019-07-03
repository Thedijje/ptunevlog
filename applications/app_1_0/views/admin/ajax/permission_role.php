<form class="form" action="<?php echo base_url('admin/permissions/save_permission')?>" method="post">
	<div class="form-group">
		<label>Select Permissions for role : <?php echo $role->role_name?></label>
		<div class="clearfix"></div>
		<?php 
		if($permissions){
			$i=1;
			foreach($permissions as $pd){
			$check_key	=	$this->lib->get_row_array('role_perm',array('role_id'=>$role->id,'perm_id'=>$pd->id));
			?>
			<div class="checkbox checkbox-success col-lg-5">
				<input type="checkbox" name="permission[]" id="inlineCheckbox<?php echo $i;?>" value="<?php echo $pd->id;?>" <?php if($check_key){ echo "checked";}?>>
				<label for="inlineCheckbox<?php echo $i;?>"> <?php echo $pd->permission_name;?> </label>
			</div>
			<?php
			if($i%2==0){
			echo "<div class='clearfix'></div>";	
			}
			$i++;
			}
		}	
		?>
		<input type="hidden" name="role_id" value="<?php echo $role->id;?>">
		<div class="clearfix"></div>
		
		
	</div>
	<div class="form-group">
		<button class="btn btn-success"><i class="fa fa-save"></i> Save</button>
	</div>
</form>