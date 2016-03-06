<!DOCTYPE html>
<html>
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script type="text/javascript"
		src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo $base_url; ?>/../js/bootstrap.min.js"></script>
	<title>Happy<?php if (isset($title)):echo "-" . $this->escape($title) ; endif; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" content="ja">
	<link type="text/css" rel="stylesheet"
	href="http://code.jquery.com/ui/1.10.3/themes/cupertino/jquery-ui.min.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $base_url; ?>/../css/style.css">
	<link href="<?php echo $base_url; ?>/../css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
	<div id="header">
		<h1><a href="<?php echo $base_url; ?>/">Happy</a></h1>
		<div id="header_menu">

<?php if($session->isAuthenticated()): ?>
				<a href="<?php echo $base_url; ?>/account/profile">編集</a>
				<a href="">ヘルプ</a>
				<a href="<?php echo $base_url; ?>/account/signout">ログアウト</a>
<?php else: ?>
				<a href="<?php echo $base_url; ?>/account/signin">ログイン</a>
				<a href="<?php echo $base_url; ?>/account/signup">アカウント登録</a>
<?php endif; ?>

		</div><!-- header_menu -->
	</div><!-- header -->
	<div id="wsMessage">
		<ul id="msg">
		</ul>
	</div>

	<div id="main">
		<?php echo $_content; ?>
	</div>

	<script>
	jQuery(function($) {
		var socket;
		// if ( $.browser.mozilla ){
		// 	socket = new MozWebSocket('ws://127.0.0.1:80/echo');
		// }else{
		// 	socket = new WebSocket('ws://127.0.0.1:80/echo');
		// }
		socket = new WebSocket('ws://127.0.0.1:80/happy2');
		// socket = new WebSocket('ws://192.168.11.5/echo');

		socket.onopen = function(msg){
			$('#wsStatus').text('online');
		};

		// socket.onmessage = function(msg){
		// 	$('#res').text( $('#res').text() + msg.data );
		// };

		// 受信したメッセージの加工とバルーン表示
		socket.onmessage = function(msg){
			var msg = msg.data;
			var msg = JSON.parse(msg);
			console.log(msg);

			// ***ToDo*** user一覧が表示されていないページでの処理方法を検討

			// 自分がクリックした場合
			if (msg.sendUserNo == myUserNo) {
				// グラフとクリック数の書き換え
				otherUserInfo(msg);
				return;

			// 表示中のユーザーか？
			} else if(msg.receiveNo == myUserNo) {
				// 自分宛なら今の仕様で表示
				toMeNewsPop(msg);

			} else {
				// 表示中の他のユーザーか？
				for (var i = 0; i < Object.keys(statuses).length; i++) {
					if (msg.receiveNo == statuses[i].usNo) {
						// グラフとクリック数の書き換え
						console.log('誰かのグラフ（全クリック数と比率）を書き換えろ!');
						otherUserInfo(msg);

					} else {
						// その他ユーザー間なら簡易表示
						console.log('簡易表示にしろ!');
						toOhterNewsPop(msg);

					}
				}
			}
		};

		socket.onclose = function(msg){
			$('#wsStatus').text('offline');
		};
		$('#wsButton').click(function(){
			socket.send($('#mes').val());
		});
	});


	function toOhterNewsPop(mess){
		return;
	}


	// クリックされたメッセージを表示
	function toMeNewsPop(mess){
		var li = '<li>' + mess.sendUserName + 'から' + mess.receiveUserName + 'へクリックされました</li>';

		var jqdiv = $('<div>')
		.appendTo($('#msg'))
		.html(li)
		.css({
			'position': 'fixed',
			'margin-right': 'auto',
			'margin-left': 'auto',
			'top': '10px',
			'background':'-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #ffec64), color-stop(1, #ffab23))',
			'background':'linear-gradient(top, #ffec64 5%, #ffab23 100%)',
			'background':'-webkit-linear-gradient(top, #ffec64 5%, #ffab23 100%)',
			'background-color':'#ffec64',
			'border-radius':'12px',
			'border':'1px solid #ffaa22',
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
		(new Audio(window.newsPopSound)).play();
	}

	</script>
</body>
</html>
