<?php $this->setLayoutVar('title', 'ログイン') ?>
<div class="container">
<div class="row">
<h2>ログイン</h2>

<p class="lead text-right">
	<a href="<?php echo $base_url; ?>/account/signup">新規ユーザ登録はこちら</a>
</p>

<form class="form-horizontal" action="<?php echo $base_url; ?>/account/authenticate" method="post" accept-charset="utf-8">
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
</div><!-- row -->
</div><!-- container -->
