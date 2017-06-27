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
function test() {
	location.href='http://yahoo.co.jp';
}

  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
	// ここにログイン時の処理を書く
      testAPI();
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into Facebook.';
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
	  FB.init({
	    appId      : '1096789150427943',
	    cookie     : true,  // enable cookies to allow the server to access
	                        // the session
	    xfbml      : true,  // parse social plugins on this page
	    version    : 'v2.8' // use graph api version 2.5
	  });

	  // Now that we've initialized the JavaScript SDK, we call
	  // FB.getLoginStatus().  This function gets the state of the
	  // person visiting this page and can return one of three states to
	  // the callback you provide.  They can be:
	  //
	  // 1. Logged into your app ('connected')
	  // 2. Logged into Facebook, but not your app ('not_authorized')
	  // 3. Not logged into Facebook and can't tell if they are logged into
	  //    your app or not.
	  //
	  // These three cases are handled in the callback function.

	  FB.getLoginStatus(function(response) {
	    statusChangeCallback(response);
	  });

  };

  // Load the SDK asynchronously
	(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.8";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
      console.log('Successful login for: ' + response.name);
      document.getElementById('status').innerHTML =
        'Thanks for logging in, ' + response.name + '!';
    });
  }


  FB.login(function(response){
    // Handle the response object, like in statusChangeCallback() in our demo
    // code.
	location.href='http://yahoo.co.jp';
  }, {scope: 'public_profile,email'});

</script>

<!--
  Below we include the Login Button social plugin. This button uses
  the JavaScript SDK to present a graphical Login button that triggers
  the FB.login() function when clicked.
-->
