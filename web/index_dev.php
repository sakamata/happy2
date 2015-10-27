<?php
require '../bootstrap.php';
require '../MiniBlogApplication.php';
require '../HappyApplication.php';

$app = new MiniBlogApplication(true);
$app = new HappyApplication(true);

$app->run();