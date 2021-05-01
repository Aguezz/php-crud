<?php

if ($_POST) {
    var_dump($_POST);
    die();
}

?>

<form action="" method="POST">
    <input type="text" name="a">
    <input type="number" min="0" name="b">
    <button type="submit">Submit</button>
</form>