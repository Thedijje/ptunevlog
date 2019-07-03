<dl class="dl-horizontal">
	<dt>Name : </dt>
	<dd><?php echo $account_info->member_name?></dd>
	
	<dt>EMail : </dt>
	<dd><?php echo $account_info->member_email?></dd>
	
	<dt>Account no : </dt>
	<dd><?php echo $account_info->account_number?></dd>
	
	<dt>Card no : </dt>
	<dd><?php echo $card_details->card_num?></dd>
	
	<dt>Expire on : </dt>
	<dd><?php echo $card_details->expiry_month.'/'.$card_details->expiry_year?></dd>
	
	<dt>Status : </dt>
	<dd><?php echo $card_details->status_name?></dd>
	
</dl>
<div class="clearfix"></div>
<a href="<?php echo base_url('admin/accounts/block_card/'.base64_encode($card_details->card_num).'/'.base64_encode($account_info->ac_id));?>" onclick="return confirm('Are you sure want to block this card?')"><button class="pull-right btn btn-danger btn-lg"><i class="fa fa-ban fa-lg"></i> Suspend Card</button></a>

<div class="clearfix"></div>