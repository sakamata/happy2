	<script type="text/javascript">
		// 変数定義
		// var usNo = statuses[viewNo].usNo;
	</script>

	<div class="container">
		<div class="row">
			<div class="viewUsers">
				<div class="col-sm-6 col-md-6 col-lg-6">
					<div class="mainStatus">
						<div class="simpleStatus text-center">
							<!-- <a href="#">?</a><br>
							<span>ON/OFF</span><br> -->
							<img src="<?php echo $href_base; ?>/img/more_Info_icon.png" alt="more_info_button" alt="more_info_button" width="54" height="54">							<img src="<?php echo $href_base; ?>/img/on_off_icon.png" alt="login_status" width="36" height="41">

							<!-- <span>Following: <?php echo $this->escape($status['ifFollowing']); ?></span> -->
				<?php
							echo $this->render('status/follow_status', array('status' => $status,));
							echo $this->render('status/follow_button', array('status' => $status, 'follow_token'=> $follow_token,));
				?>
							<!-- <span>Follower: <?php echo $this->escape($status['ifFollower']); ?></span><br> -->
							<p>No:<?php echo $this->escape($status['usNo']); ?></p>
						</div><!-- simpleStatus -->

						<div class="centerArea">
							<div class="userImageArea">
								<a href='/happy2/web/history/userHistory?viewUser=<?php echo $status['usNo']; ?>'>
								<img src="<?php echo $href_base .'/user/img/'. $status['usImg']; ?>" alt="user_photo" width="100" height="100">
								</a>
								<p>ID:<?php echo $this->escape($status['usId']); ?></p>
							</div><!-- userImageArea -->
							<div class="userInfoArea">
								<div class="userBalloon" id="userBalloon_<?php echo $this->escape($status['usNo']); ?>">
									<p><?php echo $this->escape($status['thisTimeToMeClkSum']); ?></p>
								</div>
								<div class="clearBoth">	</div>
								<div class="userInfoLeft">
									<!-- <p>all: <?php echo $this->escape($status['thisTimeAllClkSum']); ?></p> -->
									<p><?php echo $this->escape($status['roundPt']); ?> Pt</p>
								</div><!-- userInfoLeft -->
							</div><!-- userInfoArea -->
							<div class="clearBoth">	</div>
							<div class="userInfoBottom">
								<div class="otherUserGraph">
									<canvas id="otherPersentGraphCanvas_<?php echo $status['usNo']; ?>" class="otherCikCanvas" width="190" height="20">Canvasに対応したブラウザが必要です。</canvas>
								</div><!-- userGraph -->
								<a href='/happy2/web/history/userHistory?viewUser=<?php echo $status['usNo']; ?>'>
								<p><?php echo $this->escape($status['usName']); ?></p>
								</a>
							</div>
						</div><!-- userCenterArea -->
					</div><!-- mainStatus -->
					<div class="clearBoth">	</div>
				</div><!-- col -->

				<div class="col-sm-6 col-md-6 col-lg-6">
					<div class="userButtonArea">
						<div class="userButton">
							<button type="submit" id="clickAction_<?php echo $status['usNo']; ?>" class="clickAction" onclick="clickAction('post', '<?php echo $status['usNo']; ?>', '<?php echo $status['usId']; ?>', '<?php echo $status['usName']; ?>' )">
							<span id="clickSum_<?php echo $status['usNo']; ?>" class="countNumber"><?php echo $this->escape($status['MySendClkSum']); ?></span><br>Happy!</button>
							<!-- <?php echo $this->escape($thisTimeAllClkSum); ?> -->
						</div><!-- userButton -->
						<?php if (!$user) : echo $this->render('status/unLoginButton', array()); endif; ?>
						<div class="userGraph">
							<canvas id="persentGraphCanvas_<?php echo $status['usNo']; ?>" class="myCikCanvas" width="290" height="40">Canvasに対応したブラウザが必要です。</canvas>
						</div><!-- userGraph -->
					</div><!-- userButtonArea -->
				</div><!-- col -->
				<p class="clearBoth">.</p>
			</div><!-- viewUsers -->
		</div><!-- row -->
	</div><!-- container -->
	<script type="text/javascript">
		viewNo++;
	</script>
	<!-- <div class="clearBoth">	</div> -->
