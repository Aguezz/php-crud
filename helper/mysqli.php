<?php

require_once './vendor/adodb/adodb-php/adodb.inc.php';

define('HOST', '127.0.0.1');
define('USER', 'root');
define('PASSWORD', 'root');
define('DATABASE', 'tugas_proweb');

$db = NewADOConnection('mysqli');
$db->Connect(HOST, USER, PASSWORD, DATABASE);

if (!$db->IsConnected()) {
    echo $db->ErrorMsg();
}
