<script>
	// change ssl protocol
	if (document.location.protocol==="http:")
	{location.replace('https://'+window.location.host+window.location.pathname);}
</script>

<?php $this->setLayoutVar('title', 'ログイン') ?>
<div class="container">
<div class="row">
<h2>ログイン</h2>

<p class="lead text-right">
	<a href="<?php echo $href_base; ?>/account/signup">新規ユーザ登録はこちら</a>
</p>

<p>現在はプレリリース中の為、登録前に以下の点をご了承ください。</p>

<ul class="warning_list">
	<li>SSL通信を導入しました</li>
	<p>入力したパスワードや名前、ID等の情報は保護されます</p>
	<li>現在パスワードの再設定ができません。</li>
	<p>パスワードを忘れると再度ログインができなくなるのでご注意ください。</p>
	<li>データがリセットされる場合があります</li>
	<p>運用の都合上、やむなくデータを削除させていただく場合があります。</p>
</ul>

<form class="form-horizontal" action="<?php echo $req_base; ?>/account/authenticate" method="post" accept-charset="utf-8">
	<input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">

	<?php if (isset($errors) && count($errors) > 0): ?>
	<?php echo $this->render('errors', array('errors' => $errors)); ?>
	<?php endif; ?>

	<?php echo $this->render('account/inputs', array('usId' => $usId, 'usPs' => $usPs,)); ?>

<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
		<input type="submit" class="btn btn-warning btn-lg" value="ログイン">
	</div>
</div>
</form>

<p class="lead text-right">
	<a href="http://happy-project.org" target="_blank">Happy-Project.org</a>
</p>

</div><!-- row -->
</div><!-- container -->
