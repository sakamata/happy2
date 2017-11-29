<!DOCTYPE html>
<html>
<head>
<?php if($_SERVER['SERVER_ENV'] == 'product'): ?>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-10746070-5"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-10746070-5');
	</script>
<?php endif; ?>


	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script type="text/javascript"
		src="//code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo $href_base; ?>/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo $href_base; ?>/js/jquery.cookie.js"></script>
	<title>Happy<?php if (isset($title)):echo "-" . $this->escape($title) ; endif; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" content="ja">
	<link rel="shortcut icon" href="<?php echo $href_base; ?>/img/favicon16.png">
	<link type="text/css" rel="stylesheet"
	href="//code.jquery.com/ui/1.10.3/themes/cupertino/jquery-ui.min.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="/happy2/web/css/style.css?date=<?php $this->echo_filedate("css/style.css"); ?>">
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $href_base; ?>/css/style_small.css?date=<?php $this->echo_filedate("css/style_small.css"); ?>">
	<link href="<?php echo $href_base; ?>/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
	<div id="header">
		<h1><a href="<?php echo $req_base; ?>">Happy</a></h1>
		<div id="header_menu">
			<a href="<?php echo $href_base; ?>/releaseNews"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>ニュース&nbsp;</a>
			<a href="<?php echo $href_base; ?>/history/general"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span>履歴&nbsp;</a>

<?php if($session->isAuthenticated()): ?>

			<span>
				 <a href="<?php echo $href_base; ?>/account/editProfile"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>編集&nbsp;</a>
				<!-- <a href="">ヘルプ</a> -->
				<!-- <a href="<?php echo $href_base; ?>/account/signout"> ログアウト</a> -->
			</span>

<?php else: ?>

			<span>
	<?php if($action_name == 'signup' || $action_name == 'register' || $action_name == 'releaseNews'):	 ?>
				<a href="<?php echo $href_base; ?>/account/signin"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span>&nbsp;ログイン&nbsp;</a>
	<?php elseif($action_name == 'signin' || $action_name == 'authenticate' || $action_name == 'releaseNews'): ?>
				<a href="<?php echo $href_base; ?>/account/signup"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span>登録&nbsp;</a>
	<?php endif; ?>
			</span>

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
	// Cookie無しでかつ画面幅800以下ならスマホ用画面 small に設定
	var screenWidth = screen.width;
	if (screenWidth <= 800 && !$.cookie("viewType")) {
		$.cookie("viewType", "small", { expires: 30, path: '/' });
	}

	</script>
</body>
</html>
