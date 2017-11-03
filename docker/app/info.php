<?php
$hostName = '192.168.99.100';
$permitDomain ='192.168.99.100';

//$hostName = '160.16.221.26';
//$permitDomain ='160.16.221.26';

$wsPort = '8000';
$pass = 'mysql';
$dsn = 'mysql:dbname=happy2;host=192.168.99.100';
$user = 'root';

$FacebookAppId = '1096789150427943';
$FacebookAppSecret = 'e2ac3ce57875ce7cc49b4fac3729ffc8';

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
