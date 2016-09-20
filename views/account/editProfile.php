<?php $this->setLayoutVar('title', 'プロフィール編集') ?>
<div class="container">
<div class="row">

<h2>プロフィール編集</h2>

<form class="form-horizontal" action="<?php echo $href_base; ?>/account/profileConfirm" method="post" accept-charset="utf-8" enctype="multipart/form-data">
	<input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">

	<?php if (isset($errors) && count($errors) > 0): ?>
	<?php echo $this->render('errors', array('errors' => $errors)); ?>
	<?php endif; ?>

	<div class="form-group">
		<label class="col-sm-3 control-label">ユーザーID</label>
		<div class="col-sm-9">
			<p class="lead"><?php echo $this->escape($user['usId']); ?></p>
		</div>
	</div>

	<div class="form-group">
		<label class="col-xs-3 col-sm-3 control-label">名前</label>
		<div class="col-xs-12 col-sm-6 col-md-4">
			<input type="text" name="usName" class="form-control" id="InputText" placeholder="2～16文字まで" value="<?php echo $this->escape($user['usName']); ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">今の画像</label>
		<div class="col-sm-9">
			<img class="profile_img" src="<?php echo $href_base .'/user/img/'. $user['usImg']; ?>" alt="user_photo" width="100" height="100">
		</div>

	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">画像変更</label>
		<div class="col-sm-9">
			<input type='file' name='imageFile'>
			<input type="hidden" name="imageName" value="<?php echo $this->escape($user['usImg']); ?>">
			<p>5MBまで<br>画像形式: JPEG,GIF,PNG</p>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label">表示</label>
		<div class="col-sm-9">
			<label class="radio-inline">
				<input type="radio" name="viewType" value="large"
				<?php
				if (!isset($_COOKIE["viewType"])) {
					print 'checked="checked"';
				} else {
					if($_COOKIE["viewType"] === "large") {
						print 'checked="checked"';
					}
				}
				?>>通常（PC向き）
			</label>
			<label class="radio-inline">
				<input type="radio" name="viewType" value="small" <?php
				if (isset($_COOKIE["viewType"])) { $_COOKIE["viewType"] === "small" ? print 'checked="checked"' : "";};?>>縮小（スマホ向き）
			</label>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<input type="submit" class="btn btn-warning btn-lg" value="変更">
		</div>
	</div>

</form>
</div><!-- row -->
</div><!-- container -->
