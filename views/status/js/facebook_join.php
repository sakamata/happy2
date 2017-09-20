
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
		document.getElementById('fbStatus').innerHTML = 'Please log ' +
			'into this app.';
	} else {
		// Facebookにログインしてないので、不明。このhappyにログインしてるか確認（必要？）
		// document.getElementById('fbStatus').innerHTML = 'Facebookにログインしていないので不明です。';
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
		document.getElementById('fbStatus').innerHTML = response.name + 'で連携中';
			// '<input type="submit" class="fbSignoutButton" value="facebookとの連携を解除する" onclick="fbJoinRemove();">';
	});
}

</script>
