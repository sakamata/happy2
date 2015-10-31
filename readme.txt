
設定関連

ひとまず最低限動かすための設定項目

--------------------------------------------------------------
フレームワークルートURL(暫定)
http://localhost/happy2/web/


web-socket起動コマンド
ApacheのShellで以下のコマンドで起動させる

php htdocs/happy2/websocket-server/server.php
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

websocket-server設定---------------------------------

client/coffee/client.coffee
	5行目あたり
	serverUrl = 'ws://127.0.0.1:80/demo'


client/coffee/status.coffee
	5行目あたり
	serverUrl = 'ws://127.0.0.1:80/status'


server/server.php
	18行目あたり
	$server = new \WebSocket\Server('127.0.0.1', 80, false);

	27行目あたり
	複数設定可能?らしい
	$server->setAllowedOrigin('localhost');
	$server->setAllowedOrigin('happy-project.org');

	37行目あたり
	//個別のアプリケーションの登録
	$server->registerApplication('hoge', \WebSocket\Application\HogeApplication::getInstance());


server/lib/WebSocket/Server.php
	25行目あたり host port 設定
	public function __construct($host = 'localhost', $port = 80, $ssl = false)


server/lib/WebSocket/Socket.php
	31行目あたり host port 設定
	public function __construct($host = 'localhost', $port = 80, $ssl = false)

------------------------------------------------------


------------------------------------------------------
フレームワークの使い方
------------------------------------------------------

HogeController内 generateCsrfTokenについて
	generateCsrfToken( controller名 / action名 )


Viewファイル内 renderメソッドについて

	$this->render(' viewフォルダpath 拡張子不要 ' , array(' key ' => $var , ' key2 ' => $var2 ...));

	例：
	$this->render('account/inputs', array('user_name' => $user_name, 'password' => $password,));
