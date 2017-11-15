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
				<div class="simpleStatus simpleStatus-main text-center">
					<!-- <a href="#">?</a><br>
					<span>ON/OFF</span><br> -->
					<img src="<?php echo $href_base; ?>/img/more_Info_icon.png" alt="more_info_button" alt="login_status" width="54" height="54">
					<img src="<?php echo $href_base; ?>/img/online_icon.png" alt="login_status" width="36" height="41">
					<!-- ***ToDo*** follow status & followButton -->
					No: <?php echo $this->escape($headerUser['usNo']); ?>
				</div><!-- simpleStatus -->
				<div class="centerArea mainUserCenterArea">
					<div class="userImageArea">
						<a href='/happy2/web/history/userHistory?viewUser=<?php echo $headerUser['usNo']; ?>'>
						<img src="<?php echo $href_base .'/user/img/'. $headerUser['usImg']; ?>?<?php echo time(); ?>" alt="user_photo" width="100" height="100">
						</a>
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
						<a href='/happy2/web/history/userHistory?viewUser=<?php echo $headerUser['usNo']; ?>'>
						<p><?php echo $this->escape($headerUser['usName']); ?></p>
						</a>
					</div>
				</div><!-- userCenterArea -->
			</div><!-- mainStatus -->
		</div><!-- col -->

		<div class="col-sm-6 col-md-6 col-lg-6">
			<div class="userButtonArea">
				<div class="userButton">
					<button type="submit" id="clickAction_<?php echo $headerUser['usNo']; ?>" class="myClickAction" onclick="clickAction('post', '<?php echo $headerUser['usNo']; ?>', '<?php echo $headerUser['usId']; ?>', '<?php echo $this->escape_js($headerUser['usName']); ?>')"><span id="clickSum_<?php echo $headerUser['usNo']; ?>" class="countNumber"><?php echo $this->escape($headerUser['thisTimeToMeClkSum']); ?></span><br>My Happy!</button>
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
