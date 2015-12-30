<div class="pager">
	<?php if ($page != 0 ): ?>
	<form name='page_prev' action='<?php echo $this->escape($base_url); ?>'  method='post'>
		<input type='hidden' name='order' value='<?php echo $order; ?>'>
		<input type='hidden' name='usersArray' value='<?php echo $usersArray; ?>'>
		<input type='hidden' name='pager' value="<?php echo  $page - 1 ; ?>">
		<a href='javascript:page_prev.submit()'>&lt;&lt;前のページ</a>
	</form>
<?php endif; ?>
<?php
if ($userCount !== 0) {
	echo $page + 1 .' / ';
	if(ceil($userCount / $limit) > 99) {
		echo '99+';
	} else {
		echo ceil($userCount / $limit);
	}
}
?>
<?php if ($page * $limit + $limit < $userCount ): ?>
	<form name='page_next' action='<?php echo $this->escape($base_url); ?>'  method='post'>
		<input type='hidden' name='order' value='<?php echo $order; ?>'>
		<input type='hidden' name='usersArray' value='<?php echo $usersArray; ?>'>
		<input type='hidden' name='pager' value='<?php echo  $page + 1 ; ?>'>
		<a href='javascript:page_next.submit()'>次のページ&gt;&gt;</a>
	</form>
<?php endif; ?>
</div>
