<script>
	// change ssl protocol
	if (document.location.protocol==="http:")
	{location.replace('https://'+window.location.host+window.location.pathname);}
</script>

<?php $this->setLayoutVar('title', 'facebook連携解除') ?>
<div class="container">
<div class="row">
<h2>facebook連携解除</h2>

<form id="fbremovepasswordform" class="form-horizontal" action="/happy2/web/account/fbremoveauthenticate" method="post" accept-charset="utf-8">
	<input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">

	<h4>確認のため、現在のHappyのパスワードを入力してください。</h4>
	<div class="marginTop40px"></div>

	<?php if (isset($errors) && count($errors) > 0): ?>
	<?php echo $this->render('errors', array('errors' => $errors)); ?>
	<?php endif; ?>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="InputPassword">パスワード</label>
		<div class="col-sm-4">
			<input type="password" name="usPs" class="form-control"  id="InputPassword" placeholder="半角英数字" value="<?php echo $this->escape($usPs); ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label" for="InputPassword">パスワード（確認）</label>
		<div class="col-sm-4">
			<input type="password" name="usPs2" class="form-control"  id="InputPassword" placeholder="確認" value="<?php echo $this->escape($usPs2); ?>">
		</div>
	</div>
	<div class="marginTop40px"></div>
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<input type="submit" class="btn btn-default btn-lg" value="戻る" onclick="pageBack();">
			<span class="margin_btn"></span>
			<input type="submit" class="btn btn-warning btn-lg" value="解除する">
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
function pageBack(){
	document.getElementById('fbremovepasswordform').action = '<?php echo $href_base ?>/account/editProfile';
}
</script>
