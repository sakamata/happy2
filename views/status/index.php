<?php
$jsonStatuses = json_encode($statuses);
?>

<script type="text/javascript">
	var viewNo = 0;
	var statuses = JSON.parse('<?php echo $jsonStatuses; ?>');

	function send_contact_form(followingNo, followAction, f_token) {
		var f_token = '<?php echo $follow_token; ?>';
		if (followAction === 1) {
			var followAction = 'unFollow';
		} else if (followAction === 0) {
			var followAction = 'doFollow';
		}	else {
			return;
		}
		var contact_form_contents = {
			followingNo : followingNo,
			followAction : followAction,
			f_token : f_token
		};
		$.ajax({
			type: 'POST',
			url: '<?php echo $base_url; ?>/follow/follow',
			data: contact_form_contents,
			success: function(res) {
				var formId = 'follow_form_' + followingNo;
				var elem = document.getElementById(formId);
				if (followAction === 'unFollow') {
					elem.innerHTML = '<input type="hidden" name="followAction" value="follow"><input type="image" class="follow_button" src="<?php echo $base_url; ?>/../img/unfollowed_icon.png" alt="unfollow_button" value="follow">';
				} else if (followAction === 'doFollow') {
					elem.innerHTML = '<input type="hidden" name="followAction" value="follow"><input type="image" class="follow_button" src="<?php echo $base_url; ?>/../img/followed_icon.png" alt="unfollow_button" value="follow">';
				};
			},
			error: function() {
				console.log('ERROR!');
			}
		});
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
