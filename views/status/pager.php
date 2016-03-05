<div class="col-xs-8" class="col-sm-8" class="col-md-8" class="col-lg-8">
	<div class="pagerArea">
		<span>ページ</span>
<?php if ($page != 0 ): ?>
			<form name='page_prev' action='<?php echo $this->escape($base_url); ?>'  method='post'>
				<input type='hidden' name='order' value='<?php echo $order; ?>'>
				<input type='hidden' name='usersArray' value='<?php echo $usersArray; ?>'>
				<input type='hidden' name='pager' value="<?php echo  $page - 1 ; ?>">
				<a href='javascript:page_prev.submit()'><img src="<?php echo $this->escape($base_url); ?>/../img/prev_active_icon.png" alt="prev_active_icon"></a>
			</form>
<?php endif; ?>

<span>
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
</span>

<?php if ($page * $limit + $limit < $userCount ): ?>
			<form name='page_next' action='<?php echo $this->escape($base_url); ?>'  method='post'>
				<input type='hidden' name='order' value='<?php echo $order; ?>'>
				<input type='hidden' name='usersArray' value='<?php echo $usersArray; ?>'>
				<input type='hidden' name='pager' value='<?php echo  $page + 1 ; ?>'>
				<a href='javascript:page_next.submit()'><img src="<?php echo $this->escape($base_url); ?>/../img/next_active_icon.png" alt="next_active_icon"></a>
			</form>
<?php endif; ?>

	</div><!-- pager -->
</div>
