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
// change not ssl protocol
if (document.location.protocol==="https:")
{location.replace('http://'+window.location.host+window.location.pathname);}

var myUserNo = <?php echo $user['usNo']; ?>;
var viewNo = 0;
var statuses = JSON.parse('<?php echo $jsonStatuses; ?>');

// soundの準備
window.clkSoundMy = "<?php echo $href_base; ?>/sound/puyon1.mp3";
window.clkSound = "<?php echo $href_base; ?>/sound/touch1.mp3";
window.newsPopToMe = "<?php echo $href_base; ?>/sound/coin05.mp3";
window.newsPopOther = "<?php echo $href_base; ?>/sound/se_maoudamashii_onepoint26.mp3";
(new Audio(window.clkSoundMy)).load();
(new Audio(window.clkSound)).load();
(new Audio(window.newsPopToMe)).load();
(new Audio(window.newsPopOther)).load();

// ボタン押下によるクリック数の保持と書き換え
var myClickCountIncrement = function (){
	var clickSum = [];
	var numbers = [];
	var sumId = [];
	var allUsersSendClkSum = <?php echo $clickStatus['allUsersSendClkSum']; ?>;
	for (var i = 0; i < Object.keys(statuses).length; i++) {
		if (i == 0) {
			// headerUser処理
			clickSum[i] = statuses[i].thisTimeToMeClkSum;
		} else {
			clickSum[i] = statuses[i].MySendClkSum;
		}
		numbers[i] = statuses[i].usNo;
		sumId[i] = '#clickSum_' + numbers[i];
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
					(new Audio(clkSoundMy)).play();
				} else {
					(new Audio(clkSound)).play();
				}
			}
		}
		allUsersSendClkSum++;
		var percent = [];
		for (var i = 0; i < Object.keys(statuses).length; i++) {
			percent[i] = clickSum[i] / allUsersSendClkSum;
			percent[i] = percent[i] * 10000;
			percent[i] = Math.round(percent[i]) / 100;
		}
		return percent;
	}
};
var ReplaceMyClickInfo = myClickCountIncrement();


// 他のユーザークリック由来のメッセージを元にグラフと数値の書き換え
var otherClickCountIncrement = function (){
	var thisTimeToMeClkSum = [];
	var numbers = [];
	var thisTimeAllClkSum = [];
	var sumId = [];
	for (var i = 0; i < Object.keys(statuses).length; i++) {
		thisTimeToMeClkSum[i] = statuses[i].thisTimeToMeClkSum;
		numbers[i] = statuses[i].usNo;
		thisTimeAllClkSum[i] = statuses[i].thisTimeAllClkSum;
		if (i != 0) {
			sumId[i] = '#userBalloon_' + numbers[i];
		}
	}

	// クロージャ クリック総数を貯めて返す
	return function(msg){
		for (var i = 0; i < Object.keys(statuses).length; i++) {
			if (msg.sendUserNo == numbers[i]) {
				thisTimeAllClkSum[i]++;
				// 自分宛ならクリックバルーンの書き換え
				if (msg.receiveNo == myUserNo) {
					thisTimeToMeClkSum[i]++;
					if (i != 0) {
						// クリック合計の書き換え
						$(sumId[i]).html('<p>' + thisTimeToMeClkSum[i] + '</p>')
							.animate({'background-color': '#f00'})
							.animate({'background-color': '#fff'});
					}
				}
			}
		}
		var percent = [];
		for (var i = 0; i < Object.keys(statuses).length; i++) {
			percent[i] = thisTimeToMeClkSum[i] / thisTimeAllClkSum[i];
			percent[i] = percent[i] * 10000;
			percent[i] = Math.round(percent[i]) / 100;
		}
		return percent;
	}
};
var ReplaceOtherClickInfo = otherClickCountIncrement();

</script>
<div class="container">
<div class="row">
<form class="indexFrom" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>"  method="post">
	<input type='hidden' name='order' value='<?php echo $this->escape($order); ?>'>
	<div class="form-group">
		<div class="form-inline">
			<div class="col-xs-5 col-sm-4 col-md-4 col-lg-4">
				<input type="text" class="form-control input-sm" disabled="disabled" id="InputText" placeholder="検索(未実装)">
			</div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
				<button type="submit" class="btn btn-warning btn-sm"  disabled="disabled">send</button>
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
		<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
			<div id="pageTitle">
				<h2>ホーム</h2>
			</div><!-- pageTitle -->
		</div><!--  -->
		<div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
			<div id="calcStatusArea">
				<p>集計<b><?php echo $calcCount; ?></b>回　<span id="wsStatus"></span></p>
			</div>
		</div>
	</div><!-- row -->
</div><!-- container -->

<div id="main_user">
	<?php echo $this->render('status/main_user_small', array('headerUser' => $headerUser, 'user' => $user,)); ?>
</div>


<div class="container">
	<div class="row">
		<div id="orderInfoArea">
<?php
	echo $this->render('status/pager', array('page' => $page, 'limit' => $limit, 'tableCount' => $tableCount, 'order' => $order, 'usersArray' => $usersArray, 'action' => $_SERVER['REQUEST_URI'], 'method' => 'post'));

	if ($order !== null) :
		echo $this->render('status/order_changer', array('order' => $order, 'usersArray' => $usersArray, 'action' => $_SERVER['REQUEST_URI'], 'method' => 'post'));
	endif;
?>
	<form action="<?php echo $href_base; ?>/status/post" method="post" accept-charset="utf-8">
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
	<div class="container">
		<div class="row">
			<ul class="viewUsers_small">
<?php
	if(!$statuses){
		echo $this->render('status/users_null', array('usersNullMessage' => $usersNullMessage));
	} else {
		foreach ($statuses as $status):
			echo $this->render('status/users_small', array('status' => $status, 'follow_token'=> $follow_token, 'click_token'=> $click_token, 'thisTimeAllClkSum' => $headerUser['thisTimeAllClkSum']));
		endforeach;
	}
?>
			<div class="clearBoth">	</div>
			</ul><!-- viewUsers -->
		</div><!-- row -->
	</div><!-- container -->
</div><!-- statuses -->

<div class="container">
	<div class="row">
<?php
if ($page * $limit < $tableCount ) :
	echo $this->render('status/pager_footer', array('page' => $page, 'limit' => $limit, 'tableCount' => $tableCount, 'order' => $order, 'usersArray' => $usersArray, 'action' => $_SERVER['REQUEST_URI'], 'method' => 'post'));
endif;
?>
	</div><!-- row -->
</div><!-- container -->


<?php
	echo $this->render('status/js/index_js', array('hostName'=> $hostName, 'wsProtocol'=> $wsProtocol, 'wsPort'=> $wsPort, 'status' => $status, 'follow_token'=> $follow_token, 'click_token'=> $click_token, 'postSecond'=> $postSecond, 'clickStatus'=> $clickStatus, 'headerUser' => $headerUser, 'user' => $user,));
