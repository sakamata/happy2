<?php $this->setLayoutVar('title', 'ホーム') ?>
<?php
$head = [];
$head[0] = $headerUser;
if ($statuses) {
	$headAndStatuses = array_merge($head, $statuses);
} else {
	$headAndStatuses = $head;
	$status ="";
}

$jsonStatuses = json_encode($headAndStatuses);
?>

<script type="text/javascript">
var socket;
socket = new WebSocket('ws://127.0.0.1:80/happy2');

var myUserNo = <?php echo $user['usNo']; ?>;
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
				if (i == 0) {
					$(".myCountBalloon").html(clickSum[i]);	// 自分の吹き出し数の書き換え
				}
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
var ReplaceMyClickInfo = clickCountIncrement();

</script>
<div class="container">
<div class="row">
<form class="indexFrom" action="<?php echo $this->escape($base_url); ?>"  method="post">
	<input type='hidden' name='order' value='<?php echo $this->escape($order); ?>'>
	<div class="form-group">
		<div class="form-inline">
			<div class="col-xs-5 col-sm-4 col-md-4 col-lg-4">
				<input type="text" class="form-control input-sm" id="InputText" placeholder="ユーザー検索">
			</div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
				<button type="submit" class="btn btn-warning btn-sm">send</button>
			</div>
			<div class="hidden-xs col-sm-2 col-md-2 col-lg-2">
				<lavel for="InputSelect">並び替え</lavel>
			</div>
			<div class="col-xs-5 col-sm-4 col-md-4 col-lg-4">
				<select class="form-control input-sm" id="InputSelect" name="usersArray" onChange="this.form.submit()">
				<option value="newUsers" <?php echo $selected['newUsers']; ?>>登録順</option>
				<option value="following" <?php echo $selected['following']; ?>>フォロー中</option>
				<option value="followers" <?php echo $selected['followers']; ?>>フォローされている</option>
				<!-- <option value="test" <?php echo $selected['test']; ?>>テスト</option> -->
				</select>
			</div>
		</div>
	</div>
</form>
</div><!-- row -->
</div><!-- container -->

<div id="dummyIndexForm"></div>

<div class="container">
	<div class="row">
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
			<div id="pageTitle">
				<h2>ホーム</h2>
			</div><!-- pageTitle -->
		</div><!--  -->
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
			<div id="calcStatusArea">
				<p>集計回数　XX回	<span id="wsStatus"></span></p>
			</div>
		</div>
	</div><!-- row -->
</div><!-- container -->

<div id="main_user">
	<?php echo $this->render('status/main_user', array('headerUser' => $headerUser, 'user' => $user,)); ?>
</div>


<div class="container">
	<div class="row">
		<div id="orderInfoArea">
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
		echo '<p>'.$usersArrayMessage.'<b>'. count($statuses) . '</b>名を表示しています。</p>';
	endif;
	?>
		</div><!-- orderInfoArea -->
	</div><!-- row -->
</div><!-- container-fluid -->


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
	echo $this->render('status/js/index_js', array('base_url'=> $base_url, 'status' => $status, 'follow_token'=> $follow_token, 'click_token'=> $click_token, 'postSecond'=> $postSecond, 'clickStatus'=> $clickStatus, 'headerUser' => $headerUser, 'user' => $user,));
