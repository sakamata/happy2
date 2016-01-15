<?php

ini_set( "display_errors", "Off");
require_once("../../hidden/info.php");
$db = new PDO($dsn, $user, $pass);




// 要件　JSONのPOSTの値を汎用的に受け取り、生成中のオブジェクトに取り込む？

// 参考 FuelPHPでこの値を取得するには、Input::json('name')を使います。
// このコントローラーを参考にできないか？


	$followingNo = $_POST['followingNo'];
	$followAction = $_POST['followAction'];
	$follow_token = $_POST['follow_token'];

	$res = array($followingNo, $followAction);
	$res = json_encode($res);
	echo $res;
