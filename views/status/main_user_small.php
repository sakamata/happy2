<div class="container">
<div class="row">
<div class="mainUserArea_small">
	<div class="mainUserStatus_small">
		<div class="mainUserInfo_small">

			<div class="userImageArea_small">
				<img src="<?php echo $href_base .'/user/img/'. $headerUser['usImg']; ?>?<?php echo date(His); ?>" alt="user_photo" width="60" height="60">
				<!-- <canvas id="otherPersentGraphCanvas_<?php echo $headerUser['usNo']; ?>" class="otherCikCanvas" width="50" height="20">Canvasに対応したブラウザが必要です。</canvas> -->
			</div><!-- userImageArea -->
			<div class="MainUserInfoArea_small">
				<div id="headerUserBalloon_small">
					<p>
					<span class="myCountBalloon"><?php echo $this->escape($headerUser['thisTimeToMeClkSum']); ?></span>
					</p>
				</div>
				<div class="clearBoth">	</div>
				<p class="user_no">No: <?php echo $this->escape($headerUser['usNo']); ?></p>
			</div><!-- userInfoArea_small -->
			<div class="clearBoth">	</div>
			<p class="user_id">ID:<?php echo $this->escape($headerUser['usId']); ?></p>
			<div class="mainUser_name"><p><?php echo $this->escape($headerUser['usName']); ?></p></div>
		</div><!-- mainUserInfo_small -->
		<div class="mainUserButton_small">
			<button type="submit" id="clickAction_<?php echo $headerUser['usNo']; ?>" class="myClickAction_small" onclick="clickAction('post', '<?php echo $headerUser['usNo']; ?>', '<?php echo $headerUser['usId']; ?>', '<?php echo $headerUser['usName']; ?>' )">My Happy!<br><span id="clickSum_<?php echo $headerUser['usNo']; ?>" class="countNumber"><?php echo $this->escape($headerUser['thisTimeToMeClkSum']); ?></span>
			<div class="userGraph">
				<canvas id="persentGraphCanvas_<?php echo $headerUser['usNo']; ?>" class="myCikCanvas" width="130" height="16">
				Canvasに対応したブラウザが必要です。</canvas>
			</div><!-- userGraph -->
			</button>
		</div><!-- userButton_small -->
		<p class="clearBoth">.</p>

	</div><!-- mainUserStatus_small -->
</div><!-- mainUserArea_small -->
</div><!-- row -->
</div><!-- container -->

<script type="text/javascript">
viewNo++;
</script>

<!--
	TodayClick  ForYou / All: <?php echo $this->escape($headerUser['thisTimeToMeClkSum']); ?> / <?php echo $this->escape($headerUser['thisTimeAllClkSum']); ?><br>

	MySendClkSum: <span id="clickSum_<?php echo $headerUser['usNo']; ?>"><?php echo $this->escape($headerUser['thisTimeToMeClkSum']); ?></span> / <?php echo $this->escape($headerUser['thisTimeAllClkSum']); ?>
-->
