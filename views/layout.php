<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" content="ja">
	<title>Happy<?php if (isset($title)):echo "-" . $this->escape($title) ; endif; ?></title>
	<link rel="stylesheet" type="text/css" media="screen" href="/css/style.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

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
		socket.onmessage = function(msg){
			$('#res').text( $('#res').text() + msg.data );
			// $('#res').text( msg.data );
		};
		socket.onclose = function(msg){
			$('#wsStatus').text('offline');
		};
		$('#button').click(function(){
			socket.send($('#mes').val());
		});
	});
	</script>


</head>
<body>

	<div id="header">
		<h1><a href="<?php echo $base_url; ?>/">Happy ver2</a></h1>
		<div id="header_menu">
			<span>集計回数　XX回</span>
<?php if($session->isAuthenticated()): ?>
				<a href="">編集</a>
				<a href="">ヘルプ</a>
				<a href="<?php echo $base_url; ?>/account/signout">ログアウト</a>
<?php else: ?>
				<a href="<?php echo $base_url; ?>/account/signin">ログイン</a>
				<a href="<?php echo $base_url; ?>/account/signup">アカウント登録</a>
<?php endif; ?>
		</div>
	</div>

	<div id="main">
		<?php echo $_content; ?>
	</div>

</body>
</html>
