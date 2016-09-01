<?php $this->setLayoutVar('title', 'アカウント登録') ?>
<div class="container">
<div class="row">

<h2>新規アカウント登録</h2>

<p class="lead text-right">
	<a href="<?php echo $base_url; ?>/account/signin">ログインはこちら</a>
</p>


<form class="form-horizontal" action="<?php echo $base_url; ?>/account/register" method="post" accept-charset="utf-8">
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
</div><!-- row -->
</div><!-- container -->
