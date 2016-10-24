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
		if (i == 0 && statuses[0].usNo == <?php echo $myStatus['usNo']; ?>) {
			// 自分自身なら
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
				$(sumId[i]).html(clickSum[i]);	// クリック合計の書き換え
				if (myUserNo == usNo) {
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
		if (numbers[i] != myUserNo) {
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
