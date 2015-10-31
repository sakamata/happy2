<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" content="ja">
	<title>Happy<?php if (isset($title)):echo "-" . $this->escape($title) ; endif; ?></title>
	<link rel="stylesheet" type="text/css" media="screen" href="/css/style.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
</head>
<body>

	<div id="header">
		<h1><a href="<?php echo $base_url; ?>/">Happy ver2</a></h1>
	</div>

	<div id="nav">
		<p>
			<?php if($session->isAuthenticated()): ?>
				<a href="<?php echo $base_url; ?>">ホーム</a>
				<a href="<?php echo $base_url; ?>/account">アカウント</a>
			<?php else: ?>
				<a href="<?php echo $base_url; ?>/account/signin">ログイン</a>
				<a href="<?php echo $base_url; ?>/account/signup">アカウント登録</a>
			<?php endif; ?>
		</p>
	</div>

	<div id="main">
		<?php echo $_content; ?>
	</div>

</body>
</html>
