<form action="<?php echo $this->escape($base_url); ?>/follow/follow"  method="post" name="followAction" accept-charset="utf-8">
	<input type="hidden" name="follow_token" value="<?php echo $this->escape($follow_token); ?>">
	<input type="hidden" name="followingNo" value="<?php echo $this->escape($status['usNo']); ?>">

	<?php if ($status['ifFollowing'] == 0){ ?>
		<input type="hidden" name="followAction" value="follow">
		<!-- <button type="submit" class="btn btn-warning btn-sm" value="follow">Follow</button> -->
		<input type="image" id="follow_form_<?php echo $status['usNo']; ?>" class="follow_button"  src="/../img/unfollow_icon.png" alt="unfollow_button" value="follow">

		<script type="text/javascript">
			var followAction = 'follow';
			// followPost(usNo, followingNo, follow_token, followAction);
		</script>

	<?php } else { ; ?>
		<input type="hidden" name="followAction" value="unfollow">
		<!-- <button type="submit" class="btn btn-warning btn-sm" value="unfollow">unFollow</button> -->
		<input type="image" id="follow_form_<?php echo $status['usNo']; ?>" class="unfollow_button"  src="/../img/follow_plus_icon.png" alt="follow_button" value="unfollow">

		<script type="text/javascript">
			var followAction = 'unfollow';
			// followPost(usNo, followingNo, follow_token, followAction);
		</script>

	<?php }; ?>
</form>
