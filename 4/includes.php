<?php

function send_error_and_exit($error_message, $error_code="400"){
    header("HTTP/1.1 " . $error_code . " " . $error_message);
    exit();
}

$user = 'u53304';
$pass = '1449484';
$db = new PDO('mysql:host=localhost;dbname='.$user, $user, $pass, [PDO::ATTR_PERSISTENT => true]);

?>
