<script type="text/javascript">
	// 変数定義
	// var usNo = statuses[viewNo].usNo;
</script>

<li class="mainStatus_small">
	<div class="userImageArea_small">
		<a href='/happy2/web/history/userHistory?viewUser=<?php echo $status['usNo']; ?>'>
		<img src="<?php echo $href_base .'/user/img/'. $status['usImg']; ?>" alt="user_photo" width="60" height="60">
		</a>
	</div><!-- userImageArea -->
	<div class="userInfoArea_small">
		<div class="userBalloon_small" id="userBalloon_<?php echo $this->escape($status['usNo']); ?>">
			<p><?php echo $this->escape($status['thisTimeToMeClkSum']); ?></p>
		</div>
			<p class="user_no">No:<?php echo $this->escape($status['usNo']); ?></p>
	</div><!-- userInfoArea_small -->
	<div class="clearBoth">	</div>
	<div class="userInfoBottom_small">
		<div class="user_id"><p>ID:<?php echo $this->escape($status['usId']); ?></p></div>

	</div><!-- userInfoBottom -->

	<div class="userButtonArea_small">
		<div class="userButton_small">
			<button type="submit" id="clickAction_<?php echo $status['usNo']; ?>" class="clickAction_small" onclick="clickAction('post', '<?php echo $status['usNo']; ?>', '<?php echo $status['usId']; ?>', '<?php echo $status['usName']; ?>' )">
			<div class="user_name"><p><?php echo $this->escape($status['usName']); ?></p></div>
			<div class="happy_button_word">Happy! <span id="clickSum_<?php echo $status['usNo']; ?>" class="countNumber_small"><?php echo $this->escape($status['MySendClkSum']); ?></span></div>
			<div class="userGraph">
				<canvas id="persentGraphCanvas_<?php echo $status['usNo']; ?>" class="myCikCanvas" width="130" height="16">Canvasに対応したブラウザが必要です。</canvas>
			</div><!-- userGraph -->
			</button>
		</div><!-- userButton -->
	</div><!-- userButtonArea -->
</li><!-- mainStatus_small -->
<script type="text/javascript">
	viewNo++;
</script>
