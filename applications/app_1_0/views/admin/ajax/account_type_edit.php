
<h4>Edit account type: <?php echo $atype->account_type;?></h4>
<form class="form" action="<?php echo base_url('admin/account_type/update/')?>" method="post">
	<div class="form-group">
		<label>Account type name</label>
		<input type="text" class="form-control" name="account_type" value="<?php echo $atype->account_type;?>" required>
	</div>
	
	<div class="form-group">
		<label>Account type name</label>
		<textarea class="form-control" name="description" ><?php echo $atype->description;?></textarea>
		<input type="hidden" name="ac_type_id" value="<?php echo base64_encode($atype->at_id);?>">
	</div>
	
	<div class="form-group">
		<button class="btn btn-success"><i class="fa fa-save"></i> Save changes</button>
	</div>
</form>