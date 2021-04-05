<?php

define('HOST', '127.0.0.1');
define('USER', 'root');
define('PASSWORD', 'root');
define('PORT', '3306');
define('DATABASE', 'tugas_proweb');

$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE, PORT);
