<div class="col-xs-12" class="col-sm-12" class="col-md-12" class="col-lg-12">
	<div class="pagerArea_footer">
		<span>ページ</span>
<?php if ($page != 0 ): ?>
			<form name='page_prev_footer' action='<?php echo $_SERVER['SCRIPT_NAME']; ?>'  method='post'>
				<input type='hidden' name='order' value='<?php echo $order; ?>'>
				<input type='hidden' name='usersArray' value='<?php echo $usersArray; ?>'>
				<input type='hidden' name='pager' value="<?php echo  $page - 1 ; ?>">
				<a href='javascript:page_prev_footer.submit()'><img src="<?php echo $this->escape($href_base); ?>/img/prev_active_icon.png" alt="prev_active_icon"></a>
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
			<form name='page_next_footer' action='<?php echo $_SERVER['SCRIPT_NAME']; ?>'  method='post'>
				<input type='hidden' name='order' value='<?php echo $order; ?>'>
				<input type='hidden' name='usersArray' value='<?php echo $usersArray; ?>'>
				<input type='hidden' name='pager' value='<?php echo  $page + 1 ; ?>'>
				<a href='javascript:page_next_footer.submit()'><img src="<?php echo $this->escape($href_base); ?>/img/next_active_icon.png" alt="next_active_icon"></a>
			</form>
<?php endif; ?>

	</div><!-- pager -->
	<p class="lead text-center">
		<a href="http://happy-project.org" target="_blank">Happy-Project.org</a>
	</p>
</div>
