<script type="text/javascript">

// クリック率グラフの描画アニメーション
window.onload = function () {
	clickGraph('myClicks');
	clickGraph('otherClicks');
};

// viewの制御のみ分岐、計算方法等分岐させない
function clickGraph (area, argumentsPercents) {
	var allUsersSendClkSum = <?php echo $clickStatus['allUsersSendClkSum']; ?>;
	var clickSum = [];
	var thisTimeToMeClkSum = [];
	var thisTimeAllClkSum = [];
	var numbers = [];
	var cikCanvasId = [];
	var percentId = [];
	var percent = [];
	var canvas = [];
	var back = [];
	var bar = [];
	var percentText = [];

	// 画面左のグラフは otherClicks headerUser（0番）も通知由来で描画処理
	// 画面右のグラフは myClicks 自分クリック由来で描画処理
	// 左右グラフで文字サイズ変更
	// headerUser（0番）は色違い処理

	for (var i = 0; i < Object.keys(statuses).length; i++) {
		if (i == 0) {
			clickSum[i] = statuses[i].thisTimeToMeClkSum;
			var backColor = "#c8d6f0";
			var barColor = "#3668c4";
		} else {
			clickSum[i] = statuses[i].MySendClkSum;
			var backColor = "#f9ddb5";
			var barColor = "#f0ad4e";
		}
		thisTimeToMeClkSum[i] = statuses[i].thisTimeToMeClkSum;
		thisTimeAllClkSum[i] = statuses[i].thisTimeAllClkSum;

		if (area == 'myClicks') {
			percent[i] = clickSum[i] / allUsersSendClkSum;
		} else if (area == 'otherClicks'){
			percent[i] = thisTimeToMeClkSum[i] / thisTimeAllClkSum[i];
		}
		percent[i] = percent[i] * 10000;

		if (!argumentsPercents) {
			percent[i] = Math.round(percent[i]) / 100;
		} else {
			percent[i] = argumentsPercents[i];
		}

		numbers[i] = statuses[i].usNo;
		if (area === 'myClicks') {
			cikCanvasId[i] = 'persentGraphCanvas_' + numbers[i];
		} else if (area === 'otherClicks') {
			cikCanvasId[i] = 'otherPersentGraphCanvas_' + numbers[i];
		}

		canvas[i] = document.getElementById(cikCanvasId[i]);
		var width = canvas[i].width;
		var height = canvas[i].height;

		if (!canvas[i] || !canvas[i].getContext) return false;
		back[i] = canvas[i].getContext('2d');
		bar[i] =  canvas[i].getContext('2d');
		percentText[i] =  canvas[i].getContext('2d');

		// cssと同様の設定が可能
		back[i].fillStyle = backColor;
		back[i].lineWidth = 5; // 文字枠px
		back[i].lineJoin = "round"; // 交点の形状
		// 四角　塗りつぶし fillRect(x,y,w,h)
		back[i].fillRect(0, 0, width, height);

		bar[i].fillStyle = barColor;
		bar[i].fillRect(0, height*0.1, width*percent[i] / 100, height*0.8);
		percent[i] = percent[i] + "%";

		var viewType = $.cookie("viewType");
		if (area === 'myClicks') {
			percentText[i].font =  "bold 20px 'Meiryo'";
			if (viewType === 'small') {
				percentText[i].font =  "bold 12px 'Meiryo'";
			}
		} else if (area === 'otherClicks') {
			percentText[i].font =  "bold 12px 'Meiryo'";
		}

		percentText[i].textAlign = "center";
		percentText[i].strokeStyle = "#fff";
		percentText[i].strokeText(percent[i], canvas[i].width/2, canvas[i].height/2 + height*0.2);
		percentText[i].fillStyle = "#000";
		percentText[i].fillText(percent[i], canvas[i].width/2, canvas[i].height/2 + height*0.2);
	}

};

// -----------------------------

function followPost(followingNo, followAction, ifFollowing, f_token) {
	var f_token = '<?php echo $follow_token; ?>';
	if (followAction === 1) {
		var followAction = 'unFollow';
	} else if (followAction === 0) {
		var followAction = 'doFollow';
	} else {
		return;
	}

	var contact_form_contents = {
		followingNo : followingNo,
		followAction : followAction,
		f_token : f_token
	};
	$.ajax({
		type: 'POST',
		url: '<?php echo $href_base; ?>/ajaxPost/follow',
		data: contact_form_contents,
		success: function(res) {
			var formId = '#follow_form_' + followingNo;

			for (var i = 0; i < Object.keys(statuses).length; i++) {
				if(statuses[i].usNo == followingNo) {
					var iconId = "#follow_status_" + followingNo;
					var ifFollowerUser = statuses[i].ifFollower;
				}
			}

			if (followAction === 'unFollow') {
				$(formId).html('<input type="hidden" name="followAction" value="follow"><input type="image" class="follow_button" src="<?php echo $href_base; ?>/img/unfollowed_icon.png" alt="unfollow_button" value="follow">');
				if(ifFollowerUser == 0) {
					// 無関係アイコンに
					$(iconId).html('<img src="<?php echo $href_base; ?>/img/no_follow_each_other_icon.png" alt="no_follow_each_other_icon">');
				} else {
					// 片思われアイコンに
					$(iconId).html('<img src="<?php echo $href_base; ?>/img/follower_icon.png" alt="follower_icon">');
				}

			} else if (followAction === 'doFollow') {
				$(formId).html('<input type="hidden" name="followAction" value="follow"><input type="image" class="follow_button" src="<?php echo $href_base; ?>/img/followed_icon.png" alt="unfollow_button" value="follow">');
				if(ifFollowerUser == 0) {
					// 片思いアイコンに
					$(iconId).html('<img src="<?php echo $href_base; ?>/img/following_icon.png" alt="following_icon">');
				} else {
					// 両想いアイコンに
					$(iconId).html('<img src="<?php echo $href_base; ?>/img/follow_each_other_icon.png" alt="follow_each_other_icon">');
				}
			};
		},
		error: function() {
			console.log('ERROR!');
		}
	});
}

function dateFomater(date) {
	date = date.getFullYear() + '-' +
		('00' + (date.getMonth()+1)).slice(-2) + '-' +
		('00' + date.getDate()).slice(-2) + ' ' +
		('00' + date.getHours()).slice(-2) + ':' +
		('00' + date.getMinutes()).slice(-2) + ':' +
		('00' + date.getSeconds()).slice(-2);
	return date;
};

function clickObjct(usNo) {
		var clickCount = 1;
		var now = new Date();
		var date = now;
		var sqlDate = dateFomater(now);
		var post = {
			sendUser : usNo,
			clickCount : clickCount,
			timestamp : sqlDate,
			date : now
		};
		return post;
};

// Postの値を溜める
clickPool = function (post) {
	var count = 0;
	var posts = {};
	return function(post){
		if (post === 'reset') {
			posts = {};
			count = 0;
			console.log('posts reset!')
			return;
		}
		if (post) { // postがnullなら クロージャー内のpostsを返す
			key = 'no_' + count;
			if (count == 0) {
				posts[key] = post;
				count++;
				var click = 'count_zero';
			} else {
				// 同ユーザーへの時間内連続クリックなら前回のclk値に追加し、ひとまとめに
				var decmentCount = count -1;
				var decmentKey = 'no_' + decmentCount;
				decmentTime = posts[decmentKey].date;
				decmentSeUs = posts[decmentKey].sendUser;
				thisTime = post.date;
				thisSeUs = post.sendUser;
				// 連打判定時間の設定
				if (thisTime - decmentTime <= 5000 && thisSeUs == decmentSeUs) {
					posts[decmentKey].clickCount++;
				} else {
					// オブジェクトの追加
					posts[key] = post;
					count++;
				}
			}
		}
		return posts;
	}
};
var clickPool = clickPool();

function clickPost(posts) {
	if (!posts || posts == 'reset' || typeof(posts) == "function") {
		return;
	}
	var data = {};
	data["clicks"] = posts;
	token = '<?php echo $click_token; ?>';
	data["click_token"] = token;
	var now = new Date();
	var DateTime = dateFomater(now);
	data["postDateTime"] = DateTime;
	$.ajax({
		type: 'POST',
		url: '<?php echo $href_base; ?>/ajaxPost/clickPost',
		data: data,
		success: function(res) {
			console.log('clickPost success!');
			clickPool('reset');
		},
		error: function() {
			console.log('clickPost ERROR!');
		}
	})
};

var clickAction = function(action, usNo, usId, usName) {
	if (action == "intervalPost") {
		var posts = clickPool();
		var postsCount = Object.keys(posts).length;
		if (postsCount > 0) {
			clickPost(posts);
			console.log('intervalPost POST end!');
		}
		console.log('intervalPost Check end!');
		return;
	}

	// WebSocketで共有する値
	var msg = {
		receiveNo : usNo,
		receiveUserId : usId,
		receiveUserName : usName,
		sendUserNo : <?php echo $this->escape($user['usNo']); ?>,
		sendUserId : '<?php echo $this->escape($user['usId']); ?>',
		sendUserName : '<?php echo $this->escape($user['usName']); ?>',
		sendUserImage : '<?php echo $this->escape($user['usImg']); ?>'
	};

	var msg = JSON.stringify(msg);
	ID = '#clickAction_' + usNo;
	// WebSocket送信
	socket.send(msg);

	if (action == 'post') {
		// POST用のオブジェクト生成とその他の処理

		// 返り値percentsは0番を含み配列されている
		var percents = ReplaceMyClickInfo(usNo);
		var post = clickObjct(usNo);
		var posts = clickPool(post);
		clickGraph('myClicks', percents);
	}

	var postsCount = Object.keys(posts).length;
	// Object数が指定以上で強制POSTさせる
	if (postsCount >= 10) {
		clickPost(posts);
		console.log('postsCount over POST end!');
	}
};

setInterval( "clickAction('intervalPost')" , <?php echo $postSecond; ?> *1000 );


// from layout.php
var statuses;
var host = '<?php echo $_SERVER["HTTP_HOST"]; ?>';

socket = new WebSocket('<?php echo $wsProtocol; ?>://<?php echo $hostName; ?>:<?php echo $wsPort; ?>/happy2');

socket.onclose = function(msg){
	$('#wsStatus').html('通知:<b>OFF</b>');
};
socket.onerror = function(msg){
	$('#wsStatus').html('通知:<b>ERROR</b>');
};

console.log(socket);
console.log(socket.readyState);

socket.onopen = function(msg){
	$('#wsStatus').html('通知:<b>ON</b>');
	jQuery(function($) {
		// 受信したメッセージの加工とバルーン表示
		socket.onmessage = function(msg){
			var msg = msg.data;
			var msg = JSON.parse(msg);

			// 自分宛の場合
			if(msg.receiveNo == myUserNo && msg.sendUserNo != myUserNo) {
				// 受信通知
				toMeNewsPop(msg);
				// グラフとクリック数の書き換え
				var otherPercents = ReplaceOtherClickInfo(msg);
				clickGraph ('otherClicks', otherPercents);

			// 自分宛以外
			} else if (msg.sendUserNo != myUserNo) {
				// 自分宛以外は全て簡易通知
				toOhterNewsPop(msg);
			} else {
				// 自分がクリックした場合
				if (msg.sendUserNo == myUserNo) {
					var otherPercents = ReplaceOtherClickInfo(msg);
					clickGraph ('otherClicks', otherPercents);

				// 表示中のユーザーか？
				// ***ToDo*** 表示ユーザーが無い場合（statuses=nullページ）での処理
				} else {
					for (var i = 0; i < Object.keys(statuses).length; i++) {
						if (msg.receiveNo === statuses[i].usNo || msg.sendUserNo === statuses[i].usNo) {
							var otherPercents = ReplaceOtherClickInfo(msg);
							clickGraph ('otherClicks', otherPercents);
						}
					}
				}
			}
		};
	});
};


// 画面下にmassageスペースを固定
$(document).ready(function () {
	hsize = $(window).height();
	hsize = hsize - 50;
	$("#footer").css("top", hsize + "px");
});
$(window).resize(function () {
	hsize = $(window).height();
	hsize = hsize - 50;
	$("#footer").css("top", hsize + "px");
});

// 簡易クリック受信通知
function toOhterNewsPop(mess){
	var li = '<li><b>' + mess.sendUserName + '</b>から<b>' + mess.receiveUserName + '</b>へクリックされました</li>';
	var jqdiv = $('<div>')
	.appendTo($('#otherMsg'))
	.html(li)
	.css({
		'position': 'fixed',
		'left': '0',
		'bottom': '0',
		'z-index': '15',
		'heigth': '30px',
		'margin-right': 'auto',
		'margin-left': 'auto',
		// 'background':'-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #fcfcfc), color-stop(1, #cccccc))',
		// 'background':'linear-gradient(top, #fcfcfc 5%, #cccccc 100%)',
		// 'background':'-webkit-linear-gradient(top, #fcfcfc 5%, #cccccc 100%)',
		'background-color':'#fcfcfc',
		'border-radius':'5px',
		'border':'1px solid #ccc',
		'cursor':'pointer',
		'color':'#888',
		'padding':'15px',
		'text-decoration':'none',
		'list-style-type': 'none'
	})
	.fadeIn(500)
	.bind('click' , function(){
		$(this).stop(true,false)
		.fadeOut(500,function(){
			jqdiv.remove();
			// next();
		});
	});

	$('<div>').queue(function(next){
		jqdiv
		.animate({left: '5%'},1000)
		.delay(500)
		.animate({left: '30%'},2000)
		.fadeOut(1000,function(){
			jqdiv.remove();
			next();
		})
		.bind('click' , function(){
			$(this).stop(true,false)
			.fadeOut(500,function(){
				jqdiv.remove();
				next();
			});
		});
	});
	if (mess.sendUserNo == mess.receiveNo) {
		(new Audio(window.clkSoundMy)).play();
	} else {
		(new Audio(window.newsPopOther)).play();
	}
}


// 自分へのクリックメッセージを表示
function toMeNewsPop(mess){
	var li = '<li><b>' + mess.sendUserName + '</b>から<b>' + mess.receiveUserName + '</b>へクリックされました</li>';

	var jqdiv = $('<div>')
	.appendTo($('#msg'))
	.html(li)
	.css({
		'position': 'fixed',
		'margin-right': 'auto',
		'margin-left': 'auto',
		'top': '10px',
		// 'background':'-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #ffec64), color-stop(1, #ffab23))',
		// 'background':'linear-gradient(top, #ffec64 5%, #ffab23 100%)',
		// 'background':'-webkit-linear-gradient(top, #ffec64 5%, #ffab23 100%)',
		'background-color':'#ffec64',
		'border-radius':'12px',
		'border':'2px solid #ffaa22',
		'cursor':'pointer',
		'color':'#333',
		'padding':'15px',
		'text-decoration':'none',
		'list-style-type': 'none'
	})
	.fadeIn(500)
	.bind('click' , function(){
		$(this).stop(true,false)
		.fadeOut(500,function(){
			jqdiv.remove();
			// next();
		});
	});

	$('<div>').queue(function(next){
		jqdiv
		.animate({top: '200px'},1500)
		.delay(1000)
		.fadeOut(500,function(){
			jqdiv.remove();
			next();
		})
		.bind('click' , function(){
			$(this).stop(true,false)
			.fadeOut(500,function(){
				jqdiv.remove();
				next();
			});
		});
	});
	(new Audio(window.newsPopToMe)).play();
}

</script>
