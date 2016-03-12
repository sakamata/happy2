<script type="text/javascript">


// クリック率グラフの描画アニメーション
window.onload = function () {
	clickGraph('myClicks');
	clickGraph('otherClicks');
};

function clickGraph (area, argumentsPercents) {
	var allUsersSendClkSum = <?php echo $clickStatus['allUsersSendClkSum']; ?>;
	var clickSum = [];
	var numbers = [];
	var cikCanvasId = [];
	var percentId = [];
	var percent = [];
	var canvas = [];
	var back = [];
	var bar = [];
	var percentText = [];

	for (var i = 0; i < Object.keys(statuses).length; i++) {
		if (i == 0) {
			var backColor = "#c8d6f0";
			var barColor = "#3668c4";
			clickSum[i] = statuses[i].toMeClkSum;
		} else {
			clickSum[i] = statuses[i].MySendClkSum;
			var backColor = "#f9ddb5";
			var barColor = "#f0ad4e";
		}
		percent[i] = clickSum[i] / allUsersSendClkSum;
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

		if (area === 'myClicks') {
			percentText[i].font =  "bold 20px 'Meiryo'";
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
		url: '<?php echo $base_url; ?>/ajaxPost/follow',
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
				$(formId).html('<input type="hidden" name="followAction" value="follow"><input type="image" class="follow_button" src="<?php echo $base_url; ?>/../img/unfollowed_icon.png" alt="unfollow_button" value="follow">');
				if(ifFollowerUser == 0) {
					// 無関係アイコンに
					$(iconId).html('<img src="<?php echo $base_url; ?>/../img/no_follow_each_other_icon.png" alt="no_follow_each_other_icon">');
				} else {
					// 片思われアイコンに
					$(iconId).html('<img src="<?php echo $base_url; ?>/../img/follower_icon.png" alt="follower_icon">');
				}

			} else if (followAction === 'doFollow') {
				$(formId).html('<input type="hidden" name="followAction" value="follow"><input type="image" class="follow_button" src="<?php echo $base_url; ?>/../img/followed_icon.png" alt="unfollow_button" value="follow">');
				if(ifFollowerUser == 0) {
					// 片思いアイコンに
					$(iconId).html('<img src="<?php echo $base_url; ?>/../img/following_icon.png" alt="following_icon">');
				} else {
					// 両想いアイコンに
					$(iconId).html('<img src="<?php echo $base_url; ?>/../img/follow_each_other_icon.png" alt="follow_each_other_icon">');
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
		url: '<?php echo $base_url; ?>/ajaxPost/clickPost',
		data: data,
		success: function(res) {
			console.log('clickPost success!');
			console.log(data);
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

	// WebSocket送信
	ID = '#clickAction_' + usNo;
	$(document).on('click', ID, function(){
		socket.send(msg);
	});

	if (action == 'post') {
		// POST用のオブジェクト生成とその他の処理
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

</script>
