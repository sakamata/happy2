<?php $this->setLayoutVar('title', 'ログイン') ?>

<h2>ログイン</h2>

<p>
	<a href="<?php echo $base_url; ?>/account/signup">新規ユーザ登録</a>
</p>

<form action="<?php echo $base_url; ?>/account/authenticate" method="post" accept-charset="utf-8">
	<input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">

	<?php if (isset($errors) && count($errors) > 0): ?>
	<?php echo $this->render('errors', array('errors' => $errors)); ?>
	<?php endif; ?>

	<?php echo $this->render('account/inputs', array('usId' => $usId, 'usPs' => $usPs,)); ?>

	<p>
		<input type="submit" value="ログイン">
	</p>

		<a href="admin/signin">admin_signin</a>
</form>
