<?php $this->setLayoutVar('title', 'ホーム') ?>

<h2>ホーム</h2>

<div id="wsStatus"></div>
<input type="text" id="mes" placeholder="WebSocket Test">
<input type="button" id="wsButton" value="send">
<div id="res"></div>

<hr>

<form class="form-inline" action="<?php echo $this->escape($base_url); ?>"  method="post">
	<div class="form-group">
		<input type="text" class="form-control input-lg" id="InputText">
	</div>

		<button type="submit" class="btn btn-warning btn-lg">send</button>

	<div class="form-group">
		<lavel for="InputSelect">並び替え</lavel>

		<select class="form-control input-lg" id="InputSelect" name="usersArray" onChange="this.form.submit()">
			<option value="newUser_desc" <?php echo $selected['newUser_desc']; ?>>新規ユーザー順</option>
			<option value="following_desc" <?php echo $selected['following_desc']; ?>>フォロー中</option>
			<option value="followers" <?php echo $selected['followers']; ?>>フォローされている</option>
		</select>
	</div>
</form>


<form action="<?php echo $base_url; ?>/status/post" method="post" accept-charset="utf-8">
	<input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
</form>

<div id="main_user">
	<?php echo $this->render('status/main_user', array('headerUser' => $headerUser,)); ?>
</div>
<hr>

<div id="statuses">
	<?php foreach ($statuses as $status): ?>
	<?php echo $this->render('status/status', array('status' => $status)); ?>
	<?php endforeach; ?>
</div>
