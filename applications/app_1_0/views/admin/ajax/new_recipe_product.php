<div class="form-group">
	<label for="">Per person cost: <?php echo currency_symbol($recipe_info->recipes_currency).$recipe_info->recipe_pp_cost?></label>
</div>
<div class="form-group">
	<label for="">Product Image</label>
	<img src="<?php echo base_url($recipe_info->recipe_image)?>" class="img-thumnail" width="100%">
</div>
