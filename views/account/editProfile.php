<script>
// change not ssl protocol
if (document.location.protocol==="http:")
{location.replace('https://'+window.location.host+window.location.pathname);}
</script>

<?php $this->setLayoutVar('title', '設定編集') ?>
<div class="container">
<div class="row">
<h2>設定編集</h2>
<form id="editProfile" class="form-horizontal" action="/happy2/web/account/profileConfirm" method="post" accept-charset="utf-8" enctype="multipart/form-data">
	<p class="algin_right"><a href="<?php echo $href_base; ?>/account/signout"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>ログアウト</a></p>
	<input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">

	<?php if (isset($errors) && count($errors) > 0): ?>
	<?php echo $this->render('errors', array('errors' => $errors)); ?>
	<?php endif; ?>
	<?php if (isset($infos) && count($infos) > 0): ?>
	<?php echo $this->render('infos', array('infos' => $infos)); ?>
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
			<img class="profile_img" src="<?php echo $href_base .'/user/img/'. $user['usImg']; ?>?<?php echo time(); ?>" alt="user_photo" width="100" height="100">
		</div>

	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">画像変更</label>
		<div class="col-sm-9">
			<input type='file' name='imageFile' accept='image/*'>
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
		<label class="col-sm-3 control-label">Facebook連携</label>
		<div class="col-sm-9">
		<?php if (!$user['facebookId']) : ?>
			<a class="fbJoinButton" href="<?php echo $this->escape($facebookLink); ?>"><span class="fbJoinIcon">Facebook連携をする</span></a>
		<?php else: ?>
			<div id="fbStatus"><p>連携中</p></div>
			<!-- 解除前にHappyのパスワードを設定させないと駄目！ -->
			<!-- <input type="submit" class="fbSignoutButton" value="facebookとの連携を解除する" onclick="fbJoinRemove();"> -->
		<?php endif; ?>
		</div>
	</div>
	<div class="marginTop40px"></div>
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<input type="submit" class="btn btn-warning btn-lg" value="変更">
		</div>
	</div>
</form>

<div class="footer_wrapper col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="pagerArea_footer">
		<p class="lead text-center">
			<a href="http://happy-project.org" target="_blank">Happy-Project.org</a>
		</p>
	</div>
</div><!-- footer_wrapper -->
</div><!-- row -->
</div><!-- container -->

<script type="text/javascript">
var fbJoinRemove = function (){
	document.getElementById('editProfile').action="/happy2/web/account/facebookjoinremove";
}
</script>

<?php
echo $this->render('status/js/facebook_join', array(
	'user' => $user,
));
?>
