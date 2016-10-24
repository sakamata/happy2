		<span>ページ</span>
<?php if ($page != 0 ): ?>
			<form name='page_prev<?php echo $footer; ?>' action='<?php echo $action; ?>'  method='<?php echo $method; ?>'>
				<input type='hidden' name='order' value='<?php echo $order; ?>'>
				<input type='hidden' name='usersArray' value='<?php echo $usersArray; ?>'>
				<input type='hidden' name='pager' value="<?php echo  $page - 1 ; ?>">
				<input type='hidden' name='viewUser' value='<?php echo  $viewUser; ?>'>
				<a href='javascript:page_prev<?php echo $footer; ?>.submit()'><img src="<?php echo $this->escape($href_base); ?>/img/prev_active_icon.png" alt="prev_active_icon"></a>
			</form>
<?php endif; ?>

<span>
<?php
if ($tableCount !== 0) {
	echo $page + 1 .' / ';
	if(ceil($tableCount / $limit) > 99) {
		echo '99+';
	} else {
		echo ceil($tableCount / $limit);
	}
}
?>
</span>

<?php if ($page * $limit + $limit < $tableCount ): ?>
			<form name='page_next<?php echo $footer; ?>' action='<?php echo $action; ?>' method='<?php echo $method; ?>'>
				<input type='hidden' name='order' value='<?php echo $order; ?>'>
				<input type='hidden' name='usersArray' value='<?php echo $usersArray; ?>'>
				<input type='hidden' name='pager' value='<?php echo  $page + 1 ; ?>'>
				<input type='hidden' name='viewUser' value='<?php echo  $viewUser; ?>'>
				<a href='javascript:page_next<?php echo $footer; ?>.submit()'><img src="<?php echo $this->escape($href_base); ?>/img/next_active_icon.png" alt="next_active_icon"></a>
			</form>
<?php endif; ?>
