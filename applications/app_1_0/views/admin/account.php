

<div class="container-default">
	<div class="col-lg-12">
		<?php 
		$this->lib->alert_message();	
		?>
		<div class="panel panel-default">
		<?php if(isset($search_q)){ echo "<h5>Showing search result for <b>".$search_q."</b></h5>";}?>
		<div class="clearfix"></div>
		<div class="col-lg-6">
		<form class="form-inline">
				<div class="form-group">
				<input type="text" value="<?php if(isset($search_q)){ echo $search_q;}?>" placeholder="Search account by number, bank, user PAN card" name="q" class="form-control">
				<button class="btn btn-icon btn-light"><i class="fa fa-search"></i></button>
				</div>
		</form>
		</div>
		<table class="table table-hover paginated-table">
			<thead>
				<tr>
					<th>Sr</th>
					<th>Account no</th>
					<th>Bank code</th>
					<th>Member PAN</th>
					<th>Status</th>
					<th>Action</th>
				
				</tr>
				
			</thead>
			
			<tbody>
			<?php 
			if($accounts){
			$i=1;
			
				foreach($accounts as $account){
				if(!isset($search_q)):
					if($account->at_id==1){
						continue;	
					}
				endif;
				?>
				<tr>
					<td><?php echo $i;?></td>
					<td><?php echo $account->account_number?></td>
					<td><?php echo $account->bank_code?><small><small> ( <?php echo $account->bank_name;?> )</small></td>
					<td><?php if($account->member_pan_no){ echo $account->member_pan_no;}else{ echo "N/A";}?></td>
					<td>
						<span class="label label-<?php if($account->ac_status==3){ echo "warning";}else{ echo "info";}?>"><?php echo ucfirst($account->status_name);?></span>
					</td>
					<td>
						
						<a href="<?php echo base_url('admin/accounts/validate/'.base64_encode($account->ac_id))?>"><button class="btn btn-primary btn-sm btn-icon" title="View Edit and approve account"><i class="fa fa-arrow-right"></i></button></a>
						<a href="<?php echo base_url('admin/transactions/view/'.base64_encode($account->ac_id))?>"><button class="btn btn-info btn-sm btn-icon" title="View account statements"><i class="fa fa-list"></i></button></a>
						<a href="#"><button class="btn btn-danger btn-sm btn-icon" title="Remove account" disabled><i class="fa fa-trash"></i></button></a>
					</td>
				</tr>
				<?php	
				$i++;
				}
				
			}else{
				?>
				<tr>
					<td colspan="6">
						<?php $this->lib->display_alert('No Accounts yet, will be shown once added','info','info-circle');?>
					</td>
				</tr>
				<?php
			}	
			?>
			
		
			</tbody>
		
		</table>
		<?php 
	$this->js->table_pagination(NULL,NULL,TRUE);	
	?>
		</div>
		
	</div>
	<div class="clearfix"></div>

</div>
<?php 
$this->js->table_pagination($config['max_table_row_display']);	
?>



