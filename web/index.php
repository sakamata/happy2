<?php
require '../bootstrap.php';
require '../MiniBlogApplication.php';
require '../HappyApplication.php';

$app = new MiniBlogApplication(false);
$app = new HappyApplication(false);
$app->run();