<?php
$jsonStatuses = json_encode($statuses);
?>

<script type="text/javascript">
	var viewNo = 0;
	var follow_token = '<?php echo $this->escape($follow_token); ?>';
	var statuses = JSON.parse('<?php echo $jsonStatuses; ?>');
	console.log(statuses);
	// window.onload = followPost;
	function followPost(usNo, followingNo, follow_token, followAction) {
		// NGGGG!!! ajaxで呼ぶんじゃなくて POSTする
		$.ajax({
			type: "POST",
			url: "<?php echo $this->escape($base_url); ?>/follow/follow",
			data: {
				usNo: usNo,
				followingNo: followingNo,
				follow_token: follow_token,
				followAction: followAction
			}
		}).done(function () {
			// body...
		});

		var formId = 'follow_form_' + usNo;
		var elem = document.getElementById('formId');
		if (followAction === 'follow') {
			elem.innerHTML += '<img src="/../img/unfollow_icon.png">';
		} else if (followAction === 'unfollow') {
			elem.innerHTML += '<img src="/../img/follow_plus_icon.png">';

		};

	}
</script>

<?php $this->setLayoutVar('title', 'ホーム') ?>

<h2>ホーム</h2>

<div id="wsStatus"></div>
	<input type="text" id="mes" placeholder="WebSocket Test">
	<input type="button" id="wsButton" value="send">
<div id="res"></div>

<hr>
<div id="main_user">
	<?php echo $this->render('status/main_user', array('headerUser' => $headerUser,)); ?>
</div>
<hr>

<form class="form-inline" action="<?php echo $this->escape($base_url); ?>"  method="post">
	<div class="form-group">
		<input type='hidden' name='order' value='<?php echo $this->escape($order); ?>'>
		<input type="text" class="form-control input-lg" id="InputText">
	</div>

		<button type="submit" class="btn btn-warning btn-lg">send</button>

	<div class="form-group">
		<lavel for="InputSelect">並び替え</lavel>

		<select class="form-control input-lg" id="InputSelect" name="usersArray" onChange="this.form.submit()">
			<option value="newUsers" <?php echo $selected['newUsers']; ?>>新規ユーザー順</option>
			<option value="following" <?php echo $selected['following']; ?>>フォロー中</option>
			<option value="followers" <?php echo $selected['followers']; ?>>フォローされている</option>
			<option value="test" <?php echo $selected['test']; ?>>テスト</option>
		</select>
	</div>
</form>

<?php
	echo $this->render('status/pager', array('page' => $page, 'limit' => $limit, 'userCount' => $userCount, 'order' => $order, 'usersArray' => $usersArray));

	if ($order !== null) :
		echo $this->render('status/order_changer', array('order' => $order, 'usersArray' => $usersArray));
	endif;
?>

<form action="<?php echo $base_url; ?>/status/post" method="post" accept-charset="utf-8">
	<input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
</form>

<?php
	if (count($statuses) !== 0) :
		echo $usersArrayMessage.'<b>'. count($statuses) . '</b>件を表示しています。';
	endif;
?>

<div id="statuses">
<?php
	if(!$statuses){
		echo $this->render('status/users_null', array('usersNullMessage' => $usersNullMessage));
	} else {
		foreach ($statuses as $status):
			echo $this->render('status/users', array('base_url'=> $base_url, 'status' => $status, 'follow_token'=> $follow_token, 'thisUserAllClkSum' => $headerUser['thisUserAllClkSum']));
		endforeach;
	}
?>
</div>
