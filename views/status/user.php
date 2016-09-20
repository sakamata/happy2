<?php $this->setLayoutVar('title', $user['usName']) ?>

<h2><?php echo $this->escape($user['usName']); ?></h2>

<?php if (!is_null($following)): ?>
	<?php if ($following): ?>
		<p>フォローしています</p>
	<?php else: ?>
		<form action="<?php echo $href_base; ?>/follow" method="post" accept-charset="utf-8">
			<input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
			<input type="hidden" name="following_name" value="<?php echo $this->escape($user['usName']); ?>">
			<input type="submit" value="フォローする">
		</form>
	<?php endif; ?>
<?php endif; ?>

<div id="statuses">
	<?php foreach ($statuses as $status): ?>
	<?php echo $this->render('status/status', array('status' => $status)); ?>
	<?php endforeach; ?>
</div>
