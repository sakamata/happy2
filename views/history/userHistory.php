<?php $this->setLayoutVar('title', 'このユーザーの履歴') ?>
<?php $jsonStatuses = json_encode($headerUser); ?>

<script type="text/javascript">
// change not ssl protocol
if (document.location.protocol==="https:")
{location.replace('http://'+window.location.host+window.location.pathname);}

var myUserNo = <?php echo $myStatus['usNo']; ?>;
var viewNo = 0;
var statuses = JSON.parse('[<?php echo $jsonStatuses; ?>]');

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
<form class="indexFrom" action="<?php echo $req_base; ?>/history/userHistory"  method="get">
	<input type='hidden' name='order' value='<?php echo $this->escape($order); ?>'>
	<input type='hidden' name='viewUser' value='<?php echo $this->escape($viewUser); ?>'>
	<div class="form-inline">
		<div class="form-group col-xs-5 col-sm-4 col-md-4 col-lg-4">
			<input type="text" class="form-control input-sm" disabled="disabled" id="InputText" placeholder="検索(未実装)">
		</div>
		<div class="form-group col-xs-2 col-sm-2 col-md-2 col-lg-2">
			<button type="submit" class="btn btn-warning btn-sm"  disabled="disabled">send</button>
		</div>
		<div class="form-group hidden-xs col-sm-3 col-md-3 col-lg-3 form_lavel_r">
			<lavel for="InputSelect">並び替え</lavel>
		</div>
		<div class="form-group col-xs-5 col-sm-3 col-md-3 col-lg-3">
			<select class="form-control input-sm" id="InputSelect" name="usersArray" onChange="this.form.submit()">
			<option value="receiveFromHistory" <?php echo $selected['receiveFromHistory']; ?>>もらった履歴</option>
			<option value="toSendHistory" <?php echo $selected['toSendHistory']; ?>>あげた履歴</option>
			</select>
		</div>
	</div>
</form>
</div><!-- row -->
</div><!-- container -->

<div id="dummyIndexForm"></div>

<div class="container">
	<div class="row">
		<div id="pageTitle">
			<h2><?php echo $headerUser['usName']; ?>さん　<?php echo $usersArrayMessage; ?></h2>
		</div><!-- pageTitle -->
	</div><!-- row -->
</div><!-- container -->

<div id="main_user">
<?php if ($headerUser['usNo'] != $myStatus['usNo'] ) : ?>

	<?php echo $this->render('status/users', array(
		'myStatus' => $myStatus,
		'user' => $user,
		'status' => $headerUser,
		'follow_token'=> $follow_token,
		'click_token'=> $click_token,
		'thisTimeAllClkSum' => $headerUser['thisTimeAllClkSum'])
	);	?>

<?php else:  ?>

	<?php echo $this->render('status/main_user', array(
		'myStatus' => $myStatus,
		'user' => $user,
		'headerUser' => $headerUser,
		'follow_token'=> $follow_token,
		'click_token'=> $click_token,
		'thisTimeAllClkSum' => $headerUser['thisTimeAllClkSum'])
	);	?>

<?php endif; ?>

</div>

<div class="container">
	<div class="row">
		<div id="orderInfoArea">
<?php
echo $this->render('status/pager', array('page' => $page, 'limit' => $limit, 'tableCount' => $tableCount, 'order' => $order, 'viewUser' => $viewUser, 'usersArray' => $usersArray, 'action' => $_SERVER['REQUEST_URI'], 'method' => 'get'));

if ($order !== null) :
	echo $this->render('status/order_changer', array('order' => $order, 'usersArray' => $usersArray, 'action' => $_SERVER['REQUEST_URI'], 'method' => 'get'));
endif;
?>
		</div><!-- orderInfoArea -->
	</div><!-- row -->
</div><!-- container -->

<div class="container">
	<div class="row">
<?php
if(!$result){
	echo $this->render('status/users_null', array('usersNullMessage' => $usersNullMessage));
} else {
?>

		<div class="table_wrapper">
			<table class='history_table table-condensed table-bordered table-striped table-hover'>
				<thead>
					<tr>
						<th class='hidden-xs hidden-sm'>No</th>
						<th>From Happy</th>
						<th>To Happy</th>
						<th class='hidden-xs hidden-sm'>Click</th>
						<th class='hidden-md hidden-lg'>Clk</th>
						<th>Point</th>
						<th>DateTime</th>
					</tr>
				</thead>
				<tbody>
<?php
foreach ($result as $tableData):
	if ($tableData['fromId'] === $tableData['toUserId']) {
		$myHappy ="class='table_myHappy'";
	} else {
		$myHappy ='';
	}

	if ($tableData['getPt'] == 'undecided') {
		$tableData['getPt'] = '未定';
	}

	$mdTime = strtotime($tableData['dTm']);
	$Y = date('Y', $mdTime);
	$mdHis = date('m-d H:i:s', $mdTime);

	echo "<tr ". $myHappy .">\n";
	echo "<td class='hidden-xs hidden-sm'><div class='right'>".$tableData['gvnNo']."</div></td>\n";

	echo "<td class='break-all'>
	<a href='/happy2/web/history/userHistory?viewUser=".$tableData['fromNo']."'>
	<img class='history_img' src=".$href_base.'/user/img/'.$tableData['fromImg']. " alt='user_photo'><div class='history_id'>".$tableData['fromId'].'<br><b>'. $tableData['fromName']."</b></div>
	</a><div class='clearBoth'> </div></td>\n";

	echo "<td class='break-all'>
	<a href='/happy2/web/history/userHistory?viewUser=".$tableData['toUserNo']."'>
	<img class='history_img' src=".$href_base.'/user/img/'.$tableData['toUserImg']. " alt='user_photo'><div class='history_id'>".$tableData['toUserId'].'<br><b>'. $tableData['toUserName']."</b></div></a><div class='clearBoth'> </div></td>\n";

	echo "<td><div class='right'>". $tableData['formClickCount'] ."</div></td>";
	echo "<td class='hidden-xs hidden-sm'><div class='right'>". $tableData['getPt'] ."</div></td>\n";

	echo "<td class='hidden-md hidden-lg'><div class='right'>". $tableData['roundPt'] ."</div></td>\n";

	echo "<td><div class='right table_date'><span class='hidden-xs hidden-sm'>".$Y."</span><wbr> ".$mdHis."</div></td>\n";
	echo "</tr>\n";
endforeach;
?>
				</tbody>
			</table>
		</div><!-- table_wrapper -->
<?php
}
?>

	</div><!-- row -->
</div><!-- container -->
<div class="container">
	<div class="row">
<?php
if ($page * $limit < $tableCount ) :
	echo $this->render('status/pager_footer', array('page' => $page, 'limit' => $limit, 'tableCount' => $tableCount, 'order' => $order, 'viewUser' => $viewUser, 'usersArray' => $usersArray, 'action' => $_SERVER['REQUEST_URI'], 'method' => 'get'));
endif;
?>
	</div><!-- row -->
</div><!-- container -->

<?php
	echo $this->render('status/js/index_js', array(
		'hostName'=> $hostName,
		'wsProtocol'=> $wsProtocol,
		'wsPort'=> $wsPort,
		'status' => $headerUser,
		'follow_token'=> $follow_token,
		'click_token'=> $click_token,
		'postSecond'=> $postSecond,
		'clickStatus'=> $clickStatus,
		'headerUser' => $headerUser,
		'user' => $myStatus,
	));
