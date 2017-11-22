<?php
$hostName = 'localhost';
$permitDomain ='localhost';

//$hostName = '160.16.221.26';
//$permitDomain ='160.16.221.26';

$wsPort = '8000';
$pass = 'mysql';
$password = 'mysql';
$dsn = 'mysql:dbname=happy2;port=3306;host=db';
$user = 'root';

$FacebookAppId = '';
$FacebookAppSecret = '';

$wsSSL = '';
$wsProtocol ='';
if (filter_input(INPUT_SERVER, 'HTTPS', FILTER_VALIDATE_BOOLEAN)) {
        /* HTTPS */
        $wsSSL = true;
        $wsProtocol = 'wss';
} else {
        /* HTTP */
        $wsSSL = false;
        $wsProtocol = 'ws';
}

?>

