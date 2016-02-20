	<script type="text/javascript">
		// 変数定義
		var usNo = statuses[viewNo].usNo;
	</script>

	<div class="status">
		<a href="#">?</a><br>
		<span>ON/OFF</span><br>
		<span>Following: <?php echo $this->escape($status['ifFollowing']); ?></span>
<?php
		echo $this->render('status/follow_status', array('base_url' => $base_url, 'status' => $status,));
		echo $this->render('status/follow_button', array('base_url' => $base_url, 'status' => $status, 'follow_token'=> $follow_token,));
?>
		<br>
		<span>Follower: <?php echo $this->escape($status['ifFollower']); ?></span><br>
		img:<?php echo $this->escape($status['usImg']); ?><br>
		<img src="<?php echo $base_url .'/../img/'. $status['usImg']; ?>" alt="user_photo">

		<b>
		Pt: <?php echo $this->escape($status['nowPt']); ?><br>
		No: <?php echo $this->escape($status['usNo']); ?><br>
		Id: <?php echo $this->escape($status['usId']); ?><br>
		Name: <?php echo $this->escape($status['usName']); ?><br>
		TodayClick  ForYou / All: <?php echo $this->escape($status['toMeClkSum']); ?> / <?php echo $this->escape($status['allClkSum']); ?><br>
		MySendClkSum: <?php echo $this->escape($status['MySendClkSum']); ?> / <?php echo $this->escape($thisUserAllClkSum); ?>
		</b>
		<button type="submit" id="clickAction_<?php echo $status['usNo']; ?>" class="btn btn-warning btn-lg" class="clickAction" onclick="clickAction('post', '<?php echo $status['usNo']; ?>', '<?php echo $status['usId']; ?>', '<?php echo $status['usName']; ?>' )">Happy!</button>
		<div>
			<canvas id="myCikCanvas_<?php echo $status['usNo']; ?>" class="myCikCanvas" >
			Canvasに対応したブラウザが必要です。</canvas>
		</div>

	</div>

	<hr>

	<script type="text/javascript">
		viewNo++;
	</script>
