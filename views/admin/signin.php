<?php $this->setLayoutVar('title', 'ログイン') ?>

<h2>admin_signin</h2>


<form action="<?php echo $base_url; ?>/account/authenticate" method="post" accept-charset="utf-8">
	<input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">

	<?php if (isset($errors) && count($errors) > 0): ?>
	<?php echo $this->render('errors', array('errors' => $errors)); ?>
	<?php endif; ?>

	<?php echo $this->render('account/inputs', array('user_name' => $user_name, 'password' => $password,)); ?>

	<p>
		<input type="submit" value="ログイン">
	</p>
</form>