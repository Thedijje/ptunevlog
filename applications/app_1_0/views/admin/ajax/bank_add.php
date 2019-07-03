<form class="form-horizontal" action="<?php echo base_url('admin/banks/save_bank')?>" method="post">
	<div class="form-group">
		<label class="col-lg-4">Bank Name</label>
		<div class="col-lg-8">
			<input type="text" name="bank_name" value="<?php echo $bank->bank_name;?>" required class="form-control">
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-lg-4">Bank Code</label>
		<div class="col-lg-8">
			<?php echo $bank->bank_code;?>
			<input type="hidden" name="id" value="<?php echo $bank->bank_id;?>" required>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-lg-4">Bank Address</label>
		<div class="col-lg-8">
			<input type="text" class="form-control" name="bank_address" required placeholder="Please enter bank's address">
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-lg-4">Bank City and state</label>
		<div class="col-lg-4">
			<input type="text" class="form-control" name="bank_city" required placeholder="Please enter city">
		</div>
		<div class="col-lg-4">
			<input type="text" class="form-control" name="bank_state" required placeholder="Please enter state">
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-lg-4">Zip/country</label>
		<div class="col-lg-4">
			<input type="number" min="0" class="form-control" name="bank_zip" required placeholder="Specify zip code">
		</div>
		<div class="col-lg-4">
			<input type="text" class="form-control" name="bank_country" required placeholder="Specify country">
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-lg-4">Email/phone</label>
		<div class="col-lg-4">
			<input type="email" min="0" class="form-control" name="bank_email" required placeholder="Specify email">
		</div>
		<div class="col-lg-4">
			<input type="text" class="form-control" name="bank_telephone" required placeholder="Specify phone no.">
		</div>
	</div>
	
	
	<div class="form-group">
		<label class="col-lg-4">Bank Fax number</label>
		<div class="col-lg-8">
			<input type="tel" class="form-control" name="bank_fax" placeholder="Please enter bank's fax number">
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-lg-4"></label>
		<div class="col-lg-8">
			<button class="btn btn-success"><i class="fa fa-save"></i> Save</button>
		</div>
	</div>
	
	
</form>