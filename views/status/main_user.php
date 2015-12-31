<!-- ***ToDo*** 自分画面と他人画面での表示物の切り替え -->
	<a href="#">?</a><br>
	<span>ON/OFF</span><br>
	<!-- ifFollowing Follower 自分画面では不要 -->
	<span>Following: <?php echo $this->escape($headerUser['ifFollowing']); ?></span><br>
	<span>Follower: <?php echo $this->escape($headerUser['ifFollower']); ?></span><br>
	img:<?php echo $this->escape($headerUser['usImg']); ?><br>
	<b>
	Pt: <?php echo $this->escape($headerUser['nowPt']); ?><br>
	No: <?php echo $this->escape($headerUser['usNo']); ?><br>
	Id: <?php echo $this->escape($headerUser['usId']); ?><br>
	Name: <?php echo $this->escape($headerUser['usName']); ?><br>
	TodayClick  ForYou / All: <?php echo $this->escape($headerUser['thisUserToMeClkSum']); ?> / <?php echo $this->escape($headerUser['thisUserAllClkSum']); ?><br>
	</b>
