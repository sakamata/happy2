<?php
// $hoge = "hoge";
// var_dump($hoe);
// // echo '<pre>';
// var_dump($_SERVER);
// // echo '</pre>';
// phpinfo();

$fruits = array( "a", "b", "c", "d",
			// array( "AA","BB","CC")
);

var_dump($fruits);

foreach ($fruits as $key) {
	echo $key . '<br>';
	// echo $key[0];
}