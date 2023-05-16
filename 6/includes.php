<?php



function send_error_and_exit($error_message, $error_code="400"){
    header("HTTP/1.1 " . $error_code . " " . $error_message);
    exit();
}

function generate_random_string($length) {
    $symbols = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $symbols_amount = strlen($symbols);
    $str = '';
    for ($i = 0; $i < $length; $i++) {
        $str .= $symbols[random_int(0, $symbols_amount - 1)];
    }
    return $str;
}

function my_password_hash($pass){
    return password_hash($pass, PASSWORD_BCRYPT);
}

function my_verify_password($pass, $hash){
    return password_verify($pass, $hash);
}

$user = 'u53304';
$pass = '1449484';
$db = new PDO('mysql:host=localhost;dbname='.$user, $user, $pass, [PDO::ATTR_PERSISTENT => true]);

function is_admin($db){
    if (!isset($_SERVER['ADMIN_IS_LOOKING_AT_THIS_USER'])){
        try {
            $stmt = $db->prepare(
              "SELECT id FROM Person;"
            );
            $stmt->execute();
        
            if ($person = $stmt->fetch()){
                $_SERVER['ADMIN_IS_LOOKING_AT_THIS_USER'] = $person['id'];
            }
        }
        catch(PDOException $e) {
            send_error_and_exit($e->message,"500");
        }
    }
    return !empty($_SERVER['PHP_AUTH_USER']) &&
        !empty($_SERVER['PHP_AUTH_PW']) &&
        $_SERVER['PHP_AUTH_USER'] == 'admin' &&
        md5($_SERVER['PHP_AUTH_PW']) == md5('123');
}

function exit_from_admin(){

}
?>
