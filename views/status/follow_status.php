<div class="follow_status" id="follow_status_<?php echo $status['usNo']; ?>">
<?php
	if ($status['ifFollowing'] === '0' && $status['ifFollower'] === '0'){
		echo '<img src="'.$base_url.'/../img/no_follow_each_other_icon.png" alt="no_follow_each_other_icon">';
	} elseif ($status['ifFollowing'] === '1' && $status['ifFollower'] === '0') {
		echo '<img src="'.$base_url.'/../img/following_icon.png" alt="following_icon">';
	} elseif ($status['ifFollowing'] === '0' && $status['ifFollower'] === '1') {
		echo '<img src="'.$base_url.'/../img/follower_icon.png" alt="follower_icon">';
	} elseif ($status['ifFollowing'] === '1' && $status['ifFollower'] === '1') {
		echo '<img src="'.$base_url.'/../img/follow_each_other_icon.png" alt="follow_each_other_icon">';
	}
?>
</div>
