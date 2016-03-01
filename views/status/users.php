	<script type="text/javascript">
		// 変数定義
		// var usNo = statuses[viewNo].usNo;
	</script>

	<div class="viewUsers">

		<div class="simpleStatus">
			<a href="#">?</a><br>
			<span>ON/OFF</span><br>
			<!-- <span>Following: <?php echo $this->escape($status['ifFollowing']); ?></span> -->
<?php
			echo $this->render('status/follow_status', array('base_url' => $base_url, 'status' => $status,));
			echo $this->render('status/follow_button', array('base_url' => $base_url, 'status' => $status, 'follow_token'=> $follow_token,));
?>
			<!-- <span>Follower: <?php echo $this->escape($status['ifFollower']); ?></span><br> -->
		</div>
		<div class="userImageArea">
			<img src="<?php echo $base_url .'/../user/img/'. $status['usImg']; ?>" alt="user_photo">
			<br>
			<p>ID:<?php echo $this->escape($status['usId']); ?></p>
			<p>glaph</p>
		</div>
		<div class="userInfoArea">
			<div class="userBalioon">
				<p><?php echo $this->escape($status['toMeClkSum']); ?></p>
			</div>
			<div class="userInfo">
				<!-- <p>all: <?php echo $this->escape($status['allClkSum']); ?></p> -->
				<p><?php echo $this->escape($status['usName']); ?></p>
				<p><?php echo $this->escape($status['roundPt']); ?> Pt</p>
			</div>
		</div>
		<div class="userButtonArea">
			<div class="userButton">
				<!-- class="btn btn-warning btn-lg"  -->
				<button type="submit" id="clickAction_<?php echo $status['usNo']; ?>" class="clickAction" onclick="clickAction('post', '<?php echo $status['usNo']; ?>', '<?php echo $status['usId']; ?>', '<?php echo $status['usName']; ?>' )">
				<span id="clickSum_<?php echo $status['usNo']; ?>" class="countNumber"><?php echo $this->escape($status['MySendClkSum']); ?></span><br>Happy!</button>
<!-- <?php echo $this->escape($allClkSum); ?> -->
			</div>
			<div class="clearBoth">	</div>
			<div class="userGraph">
				<canvas id="persentGraphCanvas_<?php echo $status['usNo']; ?>" class="myCikCanvas" width="300" height="50">Canvasに対応したブラウザが必要です。</canvas>
			</div>
		</div>
		<div class="clearBoth">	</div>
<!--
			Id: <?php echo $this->escape($status['usId']); ?><br>
			No: <?php echo $this->escape($status['usNo']); ?><br>
			ThisTimeTheyClickPercent: <span id="clickPercent_<?php echo $status['usNo']; ?>">
			<script type="text/javascript">
				document.write(thisTimeTheyClickPercent[viewNo]);
			</script>%</span>
 -->
	</div>

	<script type="text/javascript">
		viewNo++;
	</script>
	<div class="clearBoth">	</div>
