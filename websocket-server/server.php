<?php
/* This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://sam.zoy.org/wtfpl/COPYING for more details. */

ini_set('display_errors', 1);
error_reporting(E_ALL);

require(__DIR__ . '/lib/SplClassLoader.php');

$classLoader = new SplClassLoader('WebSocket', __DIR__ . '/lib');
$classLoader->register();

// require $hostName, $permitDomain, $wsPort, $wsSSL
// localhostだと SetEnv が使えない為 requireとした
$infoPath = dirname(__FILE__) .'/../../../hidden/info.php';
require $infoPath;

// TEST SSL対応の為 第3引数 false を true に変更
$server = new \WebSocket\Server($hostName, $wsPort, $wsSSL);

// server settings:
$server->setMaxClients(200);
$server->setCheckOrigin(isset($checkOrigin) ? $checkOrigin : true);

// 通信を許可するドメイン 複数指定可能
$server->setAllowedOrigin($permitDomain);

$server->setMaxConnectionsPerIp(400);
$server->setMaxRequestsPerMinute(4000);

// Hint: Status application should not be removed as it displays usefull server informations:

//個別のアプリケーションの登録　Happy2用
$server->registerApplication('happy2', \WebSocket\Application\Happy2Application::getInstance());

$server->run();
