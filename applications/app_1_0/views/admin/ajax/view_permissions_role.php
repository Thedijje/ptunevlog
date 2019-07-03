<h3>Following permissions are available</h3>
<ul>
<?php 
	if($role_permissions){
		foreach($role_permissions as $rp){
			echo "<li class='col-lg-4'>".ucfirst(str_replace('_',' ',$this->lib->get_row('permissions','id',$rp->perm_id,'permission_name')))."</li>";
		}
	}else{
	$this->lib->display_alert('No Permission assinged right now','warning');
	}
	
?>
</ul>
<div class="clearfix"></div>