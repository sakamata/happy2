<?php
$jsonStatuses = json_encode($statuses);
?>

<script type="text/javascript">
var socket;
socket = new WebSocket('ws://127.0.0.1:80/happy2');

var viewNo = 0;
var statuses = JSON.parse('<?php echo $jsonStatuses; ?>');

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


function clickObjct(usNo) {
		var clickCount = 1;
		var date = new Date();
		var post = {
			seUs : usNo,
			clkCount : clickCount,
			dateTime : date
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
			console.log('posts,count, reset!')
			return;
		}
		if (post) { // postがnullなら　クロージャー内のpostsを返す
			key = 'no_' + count;
			if (count == 0) {
				posts[key] = post;
				count++;
				var click = 'count_zero';
			} else {
				// 同ユーザーへの時間内連続クリックなら前回のclk値に追加し、ひとまとめに
				var decmentCount = count -1;
				var decmentKey = 'no_' + decmentCount;
				decmentTime = posts[decmentKey].dateTime;
				decmentSeUs = posts[decmentKey].seUs;
				thisTime = post.dateTime;
				thisSeUs = post.seUs;
				// 連打判定時間の設定
				if (thisTime - decmentTime <= 2000 && thisSeUs == decmentSeUs) {
					posts[decmentKey].clkCount++;
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
	$.ajax({
		type: 'POST',
		url: '<?php echo $base_url; ?>/ajaxPost/clickPost',
		data: posts,
		success: function(res) {
			console.log('clickPost success!');
			clickPool('reset');
		},
		error: function() {
			console.log('clickPost ERROR!');
		}
	})
};


var clickAction = function(action, usNo, usId, usName, token) {
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

	// ***ToDo*** token処理追加


	// WebSocketで共有する値
	var msg = {
		usNo : usNo,
		usId : usId,
		usName : usName,
		sendUserNo : '<?php echo $this->escape($headerUser['usNo']); ?>',
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

	// POST用のオブジェクトを生成
	if (action == 'post') {
		var post = clickObjct(usNo);
		var posts = clickPool(post);
	}

	var postsCount = Object.keys(posts).length;
	// Object数が指定以上で強制POSTさせる
	if (postsCount >= 10) {
		clickPost(posts);
		console.log('postsCount over POST end!');
	}
};

setInterval("clickAction('intervalPost')" , 60*1000 );


// -------------------------------------------------------------


$.get("<?php echo $base_url; ?>/ajaxPost/postTimeAdjustment", function(data, status, jqXHR) {
		var clientTime = new Date();
		var serverTime = new Date(Date.parse(jqXHR.getResponseHeader("Date")));
		var diff = clientTime - serverTime;
		var accurateTime = serverTime + diff;
		var time = new Date(accurateTime);
		time = dateFomater(time);
		// console.log(time);
});

function dateFomater(date) {
	// var date;
	date = date.getFullYear() + '-' +
			('00' + (date.getMonth()+1)).slice(-2) + '-' +
			('00' + date.getDate()).slice(-2) + ' ' +
			('00' + date.getHours()).slice(-2) + ':' +
			('00' + date.getMinutes()).slice(-2) + ':' +
			('00' + date.getSeconds()).slice(-2);
			return date;
};


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
