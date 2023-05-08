<?php


include_once 'includes.php';
/**
 * Файл login.php для не авторизованного пользователя выводит форму логина.
 * При отправке формы проверяет логин/пароль и создает сессию,
 * записывает в нее логин и id пользователя.
 * После авторизации пользователь перенаправляется на главную страницу
 * для изменения ранее введенных данных.
 **/

// Отправляем браузеру правильную кодировку,
// файл login.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// Начинаем сессию.
session_start();

// В суперглобальном массиве $_SESSION хранятся переменные сессии.
// Будем сохранять туда логин после успешной авторизации.
if (!empty($_SESSION['login'])) {
  session_destroy();
  header('Location: ./');
}

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>

<form action="" method="post">
  <input name="login" />
  <input name="pass" />
  <input type="submit" value="Войти" />
</form>

<?php
}
// Иначе, если запрос был методом POST, т.е. нужно сделать авторизацию с записью логина в сессию.
else {

  $no_such_user = True;
  $uid=-1;
  try {
    if ($result = $db->query(
      "SELECT * FROM Person WHERE _login='".$_POST['login']."' && password_hash='".my_password_hash($_POST['pass'])."';"
    )){
      $no_such_user = False;
      $person = $result->fetchAll()[0];
      $uid = $person['id'];
    }
  }
  catch(PDOException $e){
      send_error_and_exit($e->message,"500");
  }
  // Выдать сообщение об ошибках.
  if ($no_such_user){
    $_SESSION['is_error']=1;
    $_SESSION['error_message']="No such login or password";
  } else {
    $_SESSION['is_error']=0;
    $_SESSION['error_message']="";
    // Если все ок, то авторизуем пользователя.
    $_SESSION['login'] = $_POST['login'];
    // Записываем ID пользователя.
    $_SESSION['uid'] = $uid; //TODO
  }

  // Делаем перенаправление.
  header('Location: ./');
}
