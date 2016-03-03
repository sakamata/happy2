<!-- ***ToDo*** 自分画面と他人画面での表示物の切り替え -->

	<!-- ifFollowing Follower 自分画面では不要 -->
	<!-- <span>Following: <?php echo $this->escape($headerUser['ifFollowing']); ?></span><br> -->
	<!-- <span>Follower: <?php echo $this->escape($headerUser['ifFollower']); ?></span><br> -->

	<?php if ($headerUser['usNo'] !== $user['usNo']){ ?>
		<?php if ($headerUser['ifFollowing'] == 0){ ?>
			<button type="submit" class="btn btn-warning btn-lg">Follow</button>
		<?php } else { ; ?>
			<button type="submit" class="btn btn-warning btn-lg">unFollow</button>
			<br>
		<?php }; ?>
	<?php }; ?>

	<a href="#">?</a><br>
	<span>ON/OFF</span><br>

	<!-- ***ToDo*** follow status & followButton -->

	<img src="<?php echo $base_url .'/../user/img/'. $headerUser['usImg']; ?>" alt="user_photo">
	<?php echo $this->escape($headerUser['usId']); ?><br>
	<?php echo $this->escape($headerUser['roundPt']); ?><br>
	<!-- No: <?php echo $this->escape($headerUser['usNo']); ?><br> -->

	<?php echo $this->escape($headerUser['usName']); ?><br>

	TodayClick  ForYou / All: <?php echo $this->escape($headerUser['toMeClkSum']); ?> / <?php echo $this->escape($headerUser['allClkSum']); ?><br>


	ThisTimeTheyClickPercent: <span id="clickPercent_<?php echo $headerUser['usNo']; ?>">
		<script type="text/javascript">
		document.write(thisTimeTheyClickPercent[viewNo]);
		viewNo++;
		</script>
		%</span>

	<button type="submit" id="clickAction_<?php echo $headerUser['usNo']; ?>" class="btn btn-primary btn-lg" class="clickAction" onclick="clickAction('post', '<?php echo $headerUser['usNo']; ?>', '<?php echo $headerUser['usId']; ?>', '<?php echo $headerUser['usName']; ?>' )">My Happy!</button>
	<div>
		<canvas id="persentGraphCanvas_<?php echo $headerUser['usNo']; ?>" class="myCikCanvas" width="300" height="50">
		Canvasに対応したブラウザが必要です。</canvas>
	</div>

	<!-- MySendClkSum: <span id="clickSum_<?php echo $headerUser['usNo']; ?>"><?php echo $this->escape($headerUser['toMeClkSum']); ?></span> / <?php echo $this->escape($headerUser['allClkSum']); ?> -->
