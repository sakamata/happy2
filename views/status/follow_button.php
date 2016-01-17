<form  method="post" name="followAction" accept-charset="utf-8" onSubmit="followPost(<?php echo $status['usNo']; ?> , <?php echo $status['ifFollowing']; ?> , <?php echo $status['ifFollowing']; ?> , follow_token); return false;">
	<input type="hidden" name="follow_token" value="<?php echo $this->escape($follow_token); ?>">
	<input type="hidden" name="followingNo" value="<?php echo $this->escape($status['usNo']); ?>">
	<div id="follow_form_<?php echo $status['usNo']; ?>">

		<?php if ($status['ifFollowing'] === '1'){ ?>

			<input type="hidden" name="followAction" value="follow">
			<input type="image" class="follow_button" src="<?php echo $base_url; ?>/../img/unfollow_icon.png" alt="unfollow_button" value="follow">

		<?php } else { ; ?>

			<input type="hidden" name="followAction" value="unfollow">
			<input type="image" class="unfollow_button" src="<?php echo $base_url; ?>/../img/follow_plus_icon.png" alt="follow_button" value="unfollow">

		<?php }; ?>
	</div>
</form>
