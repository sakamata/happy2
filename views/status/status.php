	<div class="status">
		<a href="#">?</a><br>
		<span>ON/OFF</span><br>
		<span>Follow</span><br>
		img:<?php echo $this->escape($status['usImg']); ?><br>
		<b>
		Pt: <?php echo $this->escape($status['nowPt']); ?><br>
		No: <?php echo $this->escape($status['usNo']); ?><br>
		Id: <?php echo $this->escape($status['usId']); ?><br>
		Name: <?php echo $this->escape($status['usName']); ?><br>
		TodayClick  ForYou / All: <?php echo $this->escape($status['toMeClkSum']); ?> / <?php echo $this->escape($status['allClkSum']); ?><br>
		</b>
	</div>
