<script>
	// change ssl protocol
	if (document.location.protocol==="http:")
	{location.replace('https://'+window.location.host+window.location.pathname);}
</script>

<?php $this->setLayoutVar('title', 'アカウント登録') ?>
<div class="container">
<div class="row">

<h2>新規アカウント登録</h2>

<p class="lead text-right">
	<a href="<?php echo $href_base; ?>/account/signin">ログインはこちら</a>
</p>

<p>現在はプレリリース中の為、登録前に以下の点をご了承ください。</p>
<li>SSL通信を導入しました</li>
<p>入力したパスワードは保護されます</p>

<ul class="warning_list">
	<li>ユーザー,ID,名前,プロフィール画像,クリック記録,等の情報は登録者以外にも公開されます</li>
	<li>現在パスワードの再設定ができません。</li>
	<p>パスワードを忘れると再度ログインができなくなるのでご注意ください。</p>
	<li>データがリセットされる場合があります</li>
	<p>運用の都合上、やむなくデータを削除させていただく場合があります。</p>
</ul>

<form class="form-horizontal" action="<?php echo $href_base; ?>/account/register" method="post" accept-charset="utf-8">
	<input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">

	<?php if (isset($errors) && count($errors) > 0): ?>
	<?php echo $this->render('errors', array('errors' => $errors)); ?>
	<?php endif; ?>
	<div class="form-group">
	<label class="col-sm-2 control-label">名前</label>
		<div class="col-sm-4">
			<input type="text" name="usName" class="form-control" id="InputText" placeholder="16文字まで" value="<?php echo $this->escape($usName); ?>">
		</div>
	</div>

	<?php echo $this->render('account/inputs', array('usName' => $usName, 'usId' => $usId, 'usPs' => $usPs,)); ?>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<input type="submit" class="btn btn-warning btn-lg" value="新規登録">
		</div>
	</div>
</form>

<p class="lead text-right">
	<a href="http://happy-project.org" target="_blank">Happy-Project.org</a>
</p>


</div><!-- row -->
</div><!-- container -->
