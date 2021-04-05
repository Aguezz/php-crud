<?php

require_once './helper/mysqli.php';
require_once './helper/shorthand.php';

$id = $_GET['id'];

$query = "DELETE FROM `students` WHERE `id` = ? LIMIT 1";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("s", $id);
$stmt->execute();
$stmt->close();

header('Location: index.php');
