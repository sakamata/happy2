<script type="text/javascript">

// クリック率グラフの描画アニメーション
window.onload = function () {
	clickGraph();
};

function clickGraph (argumentsPercents) {
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
		cikCanvasId[i] = 'persentGraphCanvas_' + numbers[i];
		canvas[i] = document.getElementById(cikCanvasId[i]);
		var width = canvas[i].width;
		var height = canvas[i].height;

		if (!canvas[i] || !canvas[i].getContext) return false;
		back[i] = canvas[i].getContext('2d');
		bar[i] =  canvas[i].getContext('2d');
		percentText[i] =  canvas[i].getContext('2d');

		// cssと同様の設定が可能
		back[i].fillStyle = backColor;
		back[i].lineWidth = 5; // 線幅px単位
		back[i].lineJoin = "round"; // 交点の形状指定　丸
		// 四角　塗りつぶし fillRect(x,y,w,h)
		back[i].fillRect(width*0.2/2, height*0.5/2, width*0.8, height*0.5);

		bar[i].fillStyle = barColor;
		bar[i].fillRect(width*0.2/2, height*0.5/2 + height*0.05, width*0.8 * percent[i] / 100, height*0.4);
		percent[i] = percent[i] + "%";
		percentText[i].font =  "bold 20px 'Meiryo'";
		percentText[i].textAlign = "center";
		percentText[i].strokeStyle = "#fff";
		percentText[i].strokeText(percent[i], canvas[i].width/2, canvas[i].height/2 + height*0.12);
		percentText[i].fillStyle = "#000";
		percentText[i].fillText(percent[i], canvas[i].width/2, canvas[i].height/2 + height*0.12);
	}
};

// -----------------------------

function followPost(followingNo, followAction, ifFollowing, f_token) {
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
		url: '<?php echo $base_url; ?>/ajaxPost/follow',
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
		usNo : usNo,
		usId : usId,
		usName : usName,
		sendUserNo : <?php echo $this->escape($headerUser['usNo']); ?>,
		sendUserId : '<?php echo $this->escape($headerUser['usId']); ?>',
		sendUserName : '<?php echo $this->escape($headerUser['usName']); ?>',
		sendUserImage : '<?php echo $this->escape($headerUser['usImg']); ?>'
	};
	var msg = JSON.stringify(msg);

	// WebSocket送信
	ID = '#clickAction_' + usNo;
	$(document).on('click', ID, function(){
		socket.send(msg);
	});

	if (action == 'post') {
		// POST用のオブジェクト生成とその他の処理
		var percents = ReplaceClickInfo(usNo);
		var post = clickObjct(usNo);
		var posts = clickPool(post);
		clickGraph(percents);
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
