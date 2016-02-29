<?php
$head = [];
$head[0] = $headerUser;
$headAndStatuses = array_merge($head, $statuses);
$jsonStatuses = json_encode($headAndStatuses);
// var_dump($user['usNo']);
?>

<script type="text/javascript">
var socket;
socket = new WebSocket('ws://127.0.0.1:80/happy2');

var viewNo = 0;
var statuses = JSON.parse('<?php echo $jsonStatuses; ?>');
console.log(statuses);

// 各ユーザーへ自分がクリックしたパーセンテージを求める
clickPercent	= function() {
	var thisTimeMySendClkSum = [];
	var thisTimeTheyClickPercent = [];
	var p = [];

	for (var i = 0; i < Object.keys(statuses).length; i++) {
		if (i == 0) {
			// headerUser処理
			thisTimeMySendClkSum[i] = statuses[i].toMeClkSum;
		} else {
			thisTimeMySendClkSum[i] = statuses[i].MySendClkSum;
		}
		p[i] = thisTimeMySendClkSum[i] / <?php echo $clickStatus['allUsersSendClkSum']; ?>;
		p[i] = p[i] * 10000;
		thisTimeTheyClickPercent[i] = Math.round(p[i]) / 100;
	}
	return thisTimeTheyClickPercent;
}
var  thisTimeTheyClickPercent	= clickPercent();
// console.log(thisTimeTheyClickPercent);


// -----------------------------

// クリック数の保持と書き換え
var clickCountIncrement = function (){
	var clickSum = [];
	var numbers = [];
	var sumId = [];
	var percentId = [];
	var allUsersSendClkSum = <?php echo $clickStatus['allUsersSendClkSum']; ?>;
	for (var i = 0; i < Object.keys(statuses).length; i++) {
		if (i == 0) {
			// headerUser処理
			clickSum[i] = statuses[i].toMeClkSum;
		} else {
			clickSum[i] = statuses[i].MySendClkSum;
		}
		numbers[i] = statuses[i].usNo;
		sumId[i] = '#clickSum_' + numbers[i];
		percentId[i] = '#clickPercent_' + numbers[i];
	}

	// クロージャ クリック総数を貯めて返す
	return function(usNo){
		for (var i = 0; i < Object.keys(statuses).length; i++) {
			if (usNo == numbers[i]) {
				clickSum[i]++;
				var replaceSum = clickSum[i];
				$(sumId[i]).html(clickSum[i]);	// クリック合計の書き換え
			}
		}
		allUsersSendClkSum++;
		var percent = [];
		for (var i = 0; i < Object.keys(statuses).length; i++) {
			percent[i] = clickSum[i] / allUsersSendClkSum;
			percent[i] = percent[i] * 10000;
			percent[i] = Math.round(percent[i]) / 100;
			$(percentId[i]).html(percent[i] + '%');	// パーセンテージの書き換え
		}
		return percent;
		// return replaceSum;
	}
};

var ReplaceClickInfo = clickCountIncrement();


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
			echo $this->render('status/users', array('base_url'=> $base_url, 'status' => $status, 'follow_token'=> $follow_token, 'click_token'=> $click_token, 'allClkSum' => $headerUser['allClkSum']));
		endforeach;
	}
?>
</div>

<?php
	echo $this->render('status/js/index_js', array('base_url'=> $base_url, 'status' => $status, 'follow_token'=> $follow_token, 'click_token'=> $click_token, 'postSecond'=> $postSecond, 'clickStatus'=> $clickStatus, 'headerUser' => $headerUser));
