<?php

require_once './helper/mysqli.php';
require_once './helper/shorthand.php';

$id = $_GET['id'];

$handle = $db->Prepare("DELETE FROM `students` WHERE `id` = ? LIMIT 1");
$bindVariables = [0 => $id];

$db->Execute($handle, $bindVariables);

header('Location: index.php');
