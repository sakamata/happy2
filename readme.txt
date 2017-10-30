
設定関連

ひとまず最低限動かすための設定項目

--------------------------------------------------------------
フレームワークルートURL(暫定)
http://localhost/happy2/web/

web-socket起動コマンド
Shellで以下のファイルをコマンドで起動させるか、サーバー内でデーモンで可動させる

php htdocs/happy2/websocket-server/server.php

現状coreアプリ側のルーティング設定された以下のURLでは動かない（描画されない）
http://localhost2/

ルーティング設定しない以下のダイレクトなディレクトリ指定でなら動く(ただしCSSは反映されない)
http://localhost/happy2/web/index.php/

***ToDo***
コンソールlogエラー文言でソース検索し原因究明
http headerが無いためか？

--------------------------------------------------------------

Happy2アプリの環境変数類---------------------------------------
環境変数はweb公開フォルダの兄弟フォルダに hidden フォルダを設け、その中に
info.php にて以下の様に変数を設定する。

$pass = 'hoge'; //databaseのpasswordを指定
$dsn = 'mysql:dbname=happy2;host=localhost'; // database PDOでのdsn設定
$user = 'root';	//databaseのログインユーザー名

$hostName = 'hoge.com';		// ドメインを指定 開発環境では'127.0.0.1'

$wsPort = 80; //websocket通信に使用するPort番号を指定
$permitDomain = 'localhost';	// websocket用 $server->setAllowedOrigin($permitDomain) にて使用、基本はドメイン、開発環境では'localhost'と指定
$wsSSL =  true or false // サーバー側のWebSocket設定におけるSSL通信かを判断
$wsProtocol = 'ws' or 'wss' //クライアント側のWebSocket設定におけるSSL通信かを切り替え

// HTTPS通信状態かを判定
if (filter_input(INPUT_SERVER, 'HTTPS', FILTER_VALIDATE_BOOLEAN)) {
	/* HTTPS */
	$wsSSL = true;
	$wsProtocol = 'wss';
} else {
	/* HTTP */
	$wsSSL = false;
	$wsProtocol = 'ws';
}

--------------------------------------------------------------

windows設定----------------------------------------------------
開発環境のURL追加

C:\Windows\System32\drivers\etc\hosts
を管理者権限のメモ帳で開き、設定を追加した

 localhost name resolution is handled within DNS itself.
	127.0.0.1       localhost
	127.0.0.1       localhost1
	127.0.0.1       localhost2

.htaccess設定-------------------------------------------------
	ブラウザでの httpリクエストは全て(ドキュメントルート内の)index.phpで受け取る設定に変更


<IfModule mod_rewrite.c>
	RewriteEngine On
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>

--------------------------------------------------------------

xampp設定------------------------------------------------------
現状コメントアウト中
	理由：web-socket側アプリとの同時起動が出来ないため

ドキュメントルート設定
	ServerName 設定
	Directory フレームワークのルートディレクト設定

C:\xampp\apache\conf\extra\htppd-vhosts.conf

#<VirtualHost *:80>
#    DocumentRoot "C:/xampp/htdocs/happy2\web"
#    ServerName localhost2
#    DirectoryIndex index.php index.html
#    <Directory "C:/xampp/htdocs/happy2/web">
#        AllowOverride All
#        Allow from All
#    </Directory>
#</VirtualHost>

------------------------------------------------------

PHP設定------------------------------------------------

php.iniファイルのTimezoneをmysqlやサーバーの時刻基準と合わせる事
	AdminController calcAction 内 $now 等に使用する為

例:  php.ini
	[Date]
	; Defines the default timezone used by the date functions
	; http://php.net/date.timezone
	date.timezone=Asia/Tokyo

-------------------------------------------------------

websocket-server設定---------------------------------

server/server.php

	16行目あたり

	// 以下はXAMPP 環境で可動確認
	$server = new \WebSocket\Server('127.0.0.1', 80, false);

	// 以下は本番環境ドメイン設定無し状態で可動確認
	$server = new \WebSocket\Server('160.16.57.194', 8000, false);


	27行目あたり

	複数設定可能?らしい
	// 以下はXAMPP 環境で可動確認
	$server->setAllowedOrigin('localhost');

	// 以下は本番環境ドメイン設定無し状態で可動確認
	$server->setAllowedOrigin('160.16.57.194');


	37行目あたり

	//個別のアプリケーションの登録
	$server->registerApplication('hoge', \WebSocket\Application\HogeApplication::getInstance());


server/lib/WebSocket/Server.php

	25行目あたり host port 設定

	// 以下の設定はXAMPP 環境で可動確認
	public function __construct($host = 'localhost', $port = 80, $ssl = false)

	// 以下は本番環境ドメイン設定無し状態で可動確認
	public function __construct($host = '160.16.57.194', $port = 8000, $ssl = false)


server/lib/WebSocket/Socket.php

	31行目あたり host port 設定

	// 以下の設定はいずれもXAMPP 環境で可動確認
	public function __construct($host = 'localhost', $port = 80, $ssl = false)
	public function __construct($host = 'localhost2', $port = 80, $ssl = false)

	// 以下は本番環境ドメイン設定無し状態で可動確認
	public function __construct($host = '160.16.57.194', $port = 8000, $ssl = false)

------------------------------------------------------

websocket-クライアント設定---------------------------------

	views/layout.php
	59行目あたり

	// 以下はXAMPP環境で可動を確認
	socket = new WebSocket('ws://127.0.0.1:80/happy2');

	// 以下は本番環境ドメイン設定無し状態で可動確認
	socket = new WebSocket('ws://160.16.57.194:8000/happy2');


	views/status/index.php
	18行目あたり

	// 以下の設定はXAMPP 環境で可動確認
	// socket = new WebSocket('ws://127.0.0.1:80/happy2');

	// 以下は本番環境ドメイン設定無し状態で可動確認
	socket = new WebSocket('ws://160.16.57.194:8000/happy2');


------------------------------------------------------
フレームワークの使い方
------------------------------------------------------

MySQLの接続設定
HappyApplication.php 内　configre()　内で定義
以下のpathと変数を環境によって変更する事
例:
require 'C:xampp/htdocs/hidden/info.php';

$dsn = 'mysql:dbname=happy2;host=localhost';
$user = 'admin';
$pass = 'hoge';


HogeController内 generateCsrfTokenについて

	generateCsrfToken( controller名 / action名 )
	一画面（ render() 単位）に controller名 / action名　が異なる
	フォームが複数ある場合は
	render() の際、個別に token を複数発行する

		例：
		$this->render(array(
			'A_token' => $this->generateCsrfToken('hoge/hogeAAA'),
			'B_token' => $this->generateCsrfToken('fuga/fugaBBB'),
		));

HogeController内 rerutn $this->render() について

	return $this->render(array(
		'usName' => $usName,
		'usId' => $usId,
		'usImg' => $usImg,
		'errors' => $errors,
		'_token' => $this->generateCsrfToken('account/editProfile'),
	), 'editProfile');	// ←この引数は通常はnull, HogeControllerの名称Hogeと異なる場合に設定, Viewに使用するfile名を指定



Viewファイル内 renderメソッドについて
view中にif,foreach等でさらに別ファイルでrender処理を行う際は、
送り先に必要な変数を array で渡してやること

	例:
	$this->render(' viewフォルダpath 拡張子不要 ' , array(' key ' => $var , ' key2 ' => $var2 ...));

	例：
	$this->render('account/inputs', array('usName' => $usName, 'usPs' => $usPs,));

------------------------------------------------------
【変更前】
■Viewファイル内 form等の actionの値について
	base_url に続き、使用するcontrollerのActionをpathで指定する


	<form action="<?php echo $base_url; ?>/admin/tableDummyInsert" method="post" accept-charset="utf-8">


【変更後 暫定】
■Viewファイル内 form等の actionの値について
	href_base に続き、使用するcontrollerのActionをpathで指定する

	<form action="<?php echo $href_base; ?>/admin/tableDummyInsert" method="post" accept-charset="utf-8">


■Viewファイルで他のViewを読み込む際は、使用する変数を読み込み先に渡す必要がある
例:
echo $this->render('admin/tbgvnPosts', array('_token' => $_token));

■SQLインジェクション対策で $_token　をhiddenフォームで入れる

	<input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">


■POSTの値は name="hoge" で運ばれる

	<input type="text" name="hoge" value="<?php echo $this->escape($hoge); ?>">


■controller内Actionでは　getPost('hoge')　で値を受け取れる

	例;	$PostValue = $this->request->getPost('hoge');

------------------------------------------------------

Thanks!

Convert JS date time to MySQL datetime
http://stackoverflow.com/questions/5129624/convert-js-date-time-to-mysql-datetime

画面サイズに合わせて高さを指定する3つの方法
http://weboook.blog22.fc2.com/blog-entry-411.html

PHPでGDライブラリを使って画像リサイズ（サムネイル画像を作る）をやってみる
http://unskilled.site/php%E3%81%A7gd%E3%83%A9%E3%82%A4%E3%83%96%E3%83%A9%E3%83%AA%E3%82%92%E4%BD%BF%E3%81%A3%E3%81%A6%E7%94%BB%E5%83%8F%E3%83%AA%E3%82%B5%E3%82%A4%E3%82%BA%EF%BC%88%E3%82%B5%E3%83%A0%E3%83%8D%E3%82%A4/

Sound
無料効果音で遊ぼう！
http://taira-komori.jpn.org/index.html
魔王魂
http://maoudamashii.jokersounds.com/

css3 Button Generator
http://css3button.net/
