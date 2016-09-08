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
		<h1><a href="<?php echo $base_url; ?>">Happy</a></h1>
		<div id="header_menu">

<?php if($session->isAuthenticated()): ?>
				<a href="<?php echo $base_url; ?>/account/editProfile">編集 </a>
				<!-- <a href="">ヘルプ</a> -->
				<a href="<?php echo $base_url; ?>/account/signout"> ログアウト</a>
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
	// for index_js.php
	</script>
</body>
</html>
