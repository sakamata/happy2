<!-- ***ToDo*** 自分画面と他人画面での表示物の切り替え -->

	<!-- ifFollowing Follower 自分画面では不要 -->
	<!-- <span>Following: <?php echo $this->escape($headerUser['ifFollowing']); ?></span><br> -->
	<!-- <span>Follower: <?php echo $this->escape($headerUser['ifFollower']); ?></span><br> -->
<!--
	<?php if ($headerUser['usNo'] !== $user['usNo']){ ?>
		<?php if ($headerUser['ifFollowing'] == 0){ ?>
			<button type="submit" class="btn btn-warning btn-lg">Follow</button>
		<?php } else { ; ?>
			<button type="submit" class="btn btn-warning btn-lg">unFollow</button>
			<br>
		<?php }; ?>
	<?php }; ?>
 -->
<div class="container">
	<div class="row">
	<div class="mainUser">
		<div class="col-sm-6 col-md-6 col-lg-6">
			<div class="mainStatus">
				<div class="simpleStatus">
					<a href="#">?</a><br>
					<span>ON/OFF</span><br>
					<!-- ***ToDo*** follow status & followButton -->
					No: <?php echo $this->escape($headerUser['usNo']); ?>
				</div><!-- simpleStatus -->
				<div class="userCenterArea">
					<div class="userImageArea">
						<img src="<?php echo $base_url .'/../user/img/'. $headerUser['usImg']; ?>" alt="user_photo" width="100" height="100">
						<p>ID:<?php echo $this->escape($headerUser['usId']); ?></p>
					</div><!-- userImageArea -->
					<div class="userInfoArea">
						<div id="headerUserBalloon">
							<p>
								<span class="myCountBalloon"><?php echo $this->escape($headerUser['thisTimeToMeClkSum']); ?></span>
							</p>
						</div>
						<div class="clearBoth">	</div>
						<div class="userInfoLeft">
							<p><?php echo $this->escape($headerUser['roundPt']); ?> Pt</p>
						</div><!-- userInfoLeft -->
					</div><!-- userInfoArea -->
					<div class="clearBoth">	</div>
					<div class="userInfoBottom">
						<div class="otherUserGraph">
							<canvas id="otherPersentGraphCanvas_<?php echo $headerUser['usNo']; ?>" class="otherCikCanvas" width="190" height="20">Canvasに対応したブラウザが必要です。</canvas>
						</div><!-- userGraph -->
						<p><?php echo $this->escape($headerUser['usName']); ?></p>
					</div>
				</div><!-- userCenterArea -->
			</div><!-- mainStatus -->
		</div><!-- col -->

		<div class="col-sm-6 col-md-6 col-lg-6">
			<div class="userButtonArea">
				<div class="userButton">
					<button type="submit" id="clickAction_<?php echo $headerUser['usNo']; ?>" class="myClickAction" onclick="clickAction('post', '<?php echo $headerUser['usNo']; ?>', '<?php echo $headerUser['usId']; ?>', '<?php echo $headerUser['usName']; ?>' )"><span id="clickSum_<?php echo $headerUser['usNo']; ?>" class="countNumber"><?php echo $this->escape($headerUser['thisTimeToMeClkSum']); ?></span><br>My Happy!</button>
				</div><!-- userButton -->
				<div class="userGraph">
					<canvas id="persentGraphCanvas_<?php echo $headerUser['usNo']; ?>" class="myCikCanvas" width="290" height="40">
						Canvasに対応したブラウザが必要です。</canvas>
				</div><!-- userGraph -->
			</div><!-- userButtonArea -->
		</div><!-- col -->
		<p class="clearBoth">.</p>
	</div><!-- mainUser -->
	</div><!-- row -->
</div><!-- container -->

<script type="text/javascript">
viewNo++;
</script>

<!--
	TodayClick  ForYou / All: <?php echo $this->escape($headerUser['thisTimeToMeClkSum']); ?> / <?php echo $this->escape($headerUser['thisTimeAllClkSum']); ?><br>

	MySendClkSum: <span id="clickSum_<?php echo $headerUser['usNo']; ?>"><?php echo $this->escape($headerUser['thisTimeToMeClkSum']); ?></span> / <?php echo $this->escape($headerUser['thisTimeAllClkSum']); ?>
-->
