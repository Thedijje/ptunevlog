<b>Available permissions</b>
	<hr>
	<ul class="list-inline">
		<?php 
		if($permissions){
			foreach($permissions as $pd){
			echo "<li class='col-lg-6'><i class='fa fa-circle-o fa-fw'></i> ".ucfirst(str_replace('_',' ',$pd->permission_name))."</li>";	
			}
		}	
		?>
	</ul>
	<div class="clearfix"></div>