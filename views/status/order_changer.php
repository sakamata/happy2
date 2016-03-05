<div class="col-xs-4" class="col-sm-4" class="col-md-4" class="col-lg-4">
	<div class="orderChangerArea">
		<span>並び順</span>
		<form name='order' action='<?php echo $this->escape($base_url); ?>'  method='post'>
			<input type='hidden' name='order' value='<?php echo ($order == 'ASC') ?  'DESC' : 'ASC'; ?>'>
			<input type='hidden' name='usersArray' value='<?php echo $usersArray ; ?>'>
			<a href='javascript:order.submit()'><?php echo ($order == 'ASC') ? '<img src="'. $base_url .'/../img/order_asc_icon.png" alt="order_asc_icon">' :  '<img src="'. $base_url .'/../img/order_desc_icon.png" alt="order_desc_icon">'; ?></a>
		</form>
	</div>
</div>
