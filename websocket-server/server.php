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

// 以下はXAMPP 環境で可動確認
// $server = new \WebSocket\Server('127.0.0.1', 80, false);

$server = new \WebSocket\Server('160.16.57.194', 80, false);


// $server = new \WebSocket\Server('127.0.0.1', 8000, false);
// $server = new \WebSocket\Server('192.168.11.5', 80, false);
// $server = new \WebSocket\Server('happy-project.org', 80, false);

// server settings:
$server->setMaxClients(100);
$server->setCheckOrigin(true);


// 以下はXAMPP 環境で可動確認
$server->setAllowedOrigin('localhost');

// $server->setAllowedOrigin('foo.lh');
// $server->setAllowedOrigin('192.168.11.5');
// $server->setAllowedOrigin('happy-project.org');
// $server->setAllowedOrigin('localhost2');

$server->setMaxConnectionsPerIp(100);
$server->setMaxRequestsPerMinute(2000);


// Hint: Status application should not be removed as it displays usefull server informations:
// $server->registerApplication('status', \WebSocket\Application\StatusApplication::getInstance());
// $server->registerApplication('demo', \WebSocket\Application\DemoApplication::getInstance());

//個別のアプリケーションの登録　blog記事のtest用
// $server->registerApplication('echo', \WebSocket\Application\EchoApplication::getInstance());

//個別のアプリケーションの登録　Happy2用
$server->registerApplication('happy2', \WebSocket\Application\Happy2Application::getInstance());

$server->run();
