<!DOCTYPE html>
<html>
<head>
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


	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script type="text/javascript"
		src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo $base_url; ?>/../js/jquery.thermometer.js"></script>
	<script type="text/javascript" src="<?php echo $base_url; ?>/../js/bootstrap.min.js"></script>

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
		$('#wsButton').click(function(){
			socket.send($('#mes').val());
		});
	});
	</script>

</body>
</html>
