<div class="order_changer">
	<span>並び順</span>
	<form name='order' action='<?php echo $this->escape($base_url); ?>'  method='post'>
		<input type='hidden' name='order' value='<?php echo ($order == 'ASC') ?  'DESC' : 'ASC'; ?>'>
		<input type='hidden' name='usersArray' value='<?php echo $usersArray ; ?>'>
		<a href='javascript:order.submit()'><?php echo ($order == 'ASC') ? '▲' :  '▼'; ?></a>
	</form>
</div>
