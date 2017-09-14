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
	<li>現在パスワードの再設定ができません。</li>
	<p>パスワードを忘れると再度ログインができなくなるのでご注意ください。</p>
	<li>データがリセットされる場合があります</li>
	<p>運用の都合上、やむなくデータを削除させていただく場合があります。</p>
</ul>
<ul>
	<li>ユーザーID,名前,プロフィール画像,クリック記録,等の情報は登録者以外にも公開されます</li>
</ul>
<form class="form-horizontal" action="/happy2/web/account/authenticate" method="post" accept-charset="utf-8">
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

<a href="<?php echo $this->escape($facebookLink); ?>">Facebookで登録/ログイン</a>

</form>
<!-- FB login button -->
<div class="fb-login-button" data-max-rows="1" data-size="large" data-show-faces="true" data-auto-logout-link="true" onlogin="FB.login();"></div>

<div id="status">
</div>

<p class="lead text-right">
	<a href="http://happy-project.org" target="_blank">Happy-Project.org</a>
</p>

</div><!-- row -->
</div><!-- container -->

<script>

//これはFB.getLoginStatus（）からの結果で呼び出される
function statusChangeCallback(response) {
	console.log('statusChangeCallback');
	console.log(response);

// レスポンスオブジェクトが返され、ステータスフィールドが表示
// appはその人の現在のログインステータスを知っている
// レスポンスオブジェクトに関する全ドキュメントはドキュメントにあり
// for FB.getLoginStatus（）
	if (response.status === 'connected') {
		// Logged into your app and Facebook.
		// ここにログイン時の処理を書く
		testAPI();
	} else if (response.status === 'not_authorized') {
		//Facebookにログイン、happyアプリにはログインしてない
		document.getElementById('status').innerHTML = 'Please log ' +
			'into this app.';
	} else {
		// Facebookにログインしてないので、不明。このhappyにログインしてるか確認（必要？）
		document.getElementById('status').innerHTML = 'Please log ' +
			'into Facebook.';
	}
}

// この関数は、ログインボタンが終了したときに呼び出されます。
// 下記のサンプルコードで、onloginハンドラを参照してください。
function checkLoginState() {
	FB.getLoginStatus(function(response) {
		statusChangeCallback(response);
	});
}

window.fbAsyncInit = function() {
	FB.init({
		appId	: '1096789150427943',
		cookie	: true,	// enable cookies to allow the server to access
						// the session
		xfbml	: true,	// parse social plugins on this page
		version	: 'v2.8' // use graph api version 2.5
	});

	// JavaScript SDKを初期化したので、	FB.getLoginStatus（）。この関数は、
	// このページを訪れる人は、あなたが提供するコールバックに3つのうちの1つを返すことができます。
	// それらは次のようになります。

	// 1.あなたのアプリにログインしました（「接続済み」）
	// 2. Facebookにはログインしていますが、あなたのアプリはありません（ 'not_authorized'）。
	// 3. Facebookにログインせず、あなたのアプリにログインしているかどうかを知ることができません。
	// これらの3つのケースは、コールバック関数で処理されます。

	FB.getLoginStatus(function(response) {
		statusChangeCallback(response);
	});
};

// SDKを非同期でロードする
(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.8";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

//ここでは、ログインが成功した後、Graph APIの非常に簡単なテストを実行します。
// この呼び出しが行われたときは、statusChangeCallback（）を参照してください。
function testAPI() {
	console.log('Welcome!	Fetching your information.... ');
	FB.api('/me', function(response) {
		console.log('Successful login for: ' + response.name);
		console.log(response);
		document.getElementById('status').innerHTML =
			'Thanks for logging in, ' + response.name + '!';
	});
}

</script>
