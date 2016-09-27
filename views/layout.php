<!DOCTYPE html>
<html>
<head>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script type="text/javascript"
		src="//code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo $href_base; ?>/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo $href_base; ?>/js/jquery.cookie.js"></script>
	<title>Happy<?php if (isset($title)):echo "-" . $this->escape($title) ; endif; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" content="ja">
	<link type="text/css" rel="stylesheet"
	href="//code.jquery.com/ui/1.10.3/themes/cupertino/jquery-ui.min.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="/happy2/web/css/style.css">
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $href_base; ?>/css/style_small.css">
	<link href="<?php echo $href_base; ?>/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
	<div id="header">
		<h1><a href="<?php echo $req_base; ?>">Happy</a></h1>
		<div id="header_menu">
			<a href="<?php echo $href_base; ?>/history/general">履歴</a> |

<?php if($session->isAuthenticated()): ?>
				<a href="<?php echo $href_base; ?>/account/editProfile"> 編集 </a>|
				<!-- <a href="">ヘルプ</a> -->
				<a href="<?php echo $href_base; ?>/account/signout"> ログアウト</a>
<?php else: ?>
				<a href="<?php echo $href_base; ?>/account/signin"> ログイン</a> |
				<a href="<?php echo $href_base; ?>/account/signup"> アカウント登録</a>
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
