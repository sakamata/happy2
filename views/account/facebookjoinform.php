<?php $this->setLayoutVar('title', 'Facebookアカウント認証') ?>
<div class="container">
<div class="row">
<h2>ようこそ&nbsp;<?php echo $this->escape($currentUsId); ?>&nbsp;さん<br>Facebookアカウントの認証ができました。</h2>

<form class="form-horizontal" action="/happy2/web/account/facebookjoinregister" method="post" accept-charset="utf-8">
	<input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
	<h3>Q:Happyのアカウントを既に持っていますか？</h3>

	<?php if (isset($errorsSiginup) && count($errorsSiginup) > 0): ?>
	<?php echo $this->render('errors', array('errors' => $errorsSiginup)); ?>
	<?php endif; ?>

	<h3 class="marginTop70px">A1:持っていない</h3>
	<p>Happyで使用するIDを登録してください。(半角英数字20文字まで)	</p>
	<div class="form-group">
		<label class="col-sm-2 control-label">ユーザーID</label>
		<div class="col-sm-4">
			<input type="text" name="usIdSignup" class="form-control" id="InputText" placeholder="半角英数字20文字まで" style="ime-mode:disabled;" value="<?php echo $this->escape($usIdSignup); ?>">
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<input type="submit" class="btn btn-warning btn-lg" value="新規登録 / ログイン">
		</div>
	</div>

	<?php if (isset($errorsJoin) && count($errorsJoin) > 0): ?>
	<?php echo $this->render('errors', array('errors' => $errorsJoin)); ?>
	<?php endif; ?>

	<h3 class="marginTop70px">A2:持っている</h3>
	<p>アカウントを連携します。HappyのユーザーIDとパスワードを入力してください。</p>
	<?php echo $this->render('account/inputs', array('usId' => $usIdJoin, 'usPs' => $usPs,)); ?>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<input type="submit" class="btn btn-warning btn-lg" value="連携してログイン">
		</div>
	</div>

	<h3 class="marginTop70px">A3:持っているが忘れた</h3>
	<p>Facebookメッセージでご連絡頂けば、確認の後、連携作業を行います。<br>このまま『登録/ログイン』を行っていただいても結構です。<br></p>
</form>

</div><!-- row -->
</div><!-- container -->

<div class="container">
	<div class="row">
		<div class="footer_wrapper col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<p class="lead text-center">
				<a href="http://happy-project.org" target="_blank">Happy-Project.org</a>
			</p>
		</div>
	</div><!-- row -->
</div><!-- container -->
