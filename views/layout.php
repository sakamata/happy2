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

	<script type="text/javascript">
		var statuses;
	</script>

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
	<div id="toMeWsMessage">
		<ul id="msg"> </ul>
	</div>

	<div id="main">
		<?php echo $_content; ?>
	</div>

	<div id="footer">
		<ul id="otherMsg"> </ul>
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
		socket.onclose = function(msg){
			$('#wsStatus').text('offline');
		};

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
			} else {
				// 自分宛以外は全て簡易通知
				toOhterNewsPop(msg);

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
			'background':'-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #fcfcfc), color-stop(1, #cccccc))',
			'background':'linear-gradient(top, #fcfcfc 5%, #cccccc 100%)',
			'background':'-webkit-linear-gradient(top, #fcfcfc 5%, #cccccc 100%)',
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
		(new Audio(window.newsPopSound)).play();
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
