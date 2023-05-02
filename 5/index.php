<?php
/**
 * Реализовать возможность входа с паролем и логином с использованием
 * сессии для изменения отправленных данных в предыдущей задаче,
 * пароль и логин генерируются автоматически при первоначальной отправке формы.
 */

// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // Массив для временного хранения сообщений пользователю.
  $messages = array();

  // В суперглобальном массиве $_COOKIE PHP хранит все имена и значения куки текущего запроса.
  // Выдаем сообщение об успешном сохранении.
  if (!empty($_COOKIE['save'])) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('save', '', 100000);
    setcookie('login', '', 100000);
    setcookie('pass', '', 100000);
    // Выводим сообщение пользователю.
    $messages[] = 'Спасибо, результаты сохранены.';
    // Если в куках есть пароль, то выводим сообщение.
    if (!empty($_COOKIE['pass'])) {
      $messages[] = sprintf('Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
        и паролем <strong>%s</strong> для изменения данных.',
        strip_tags($_COOKIE['login']),
        strip_tags($_COOKIE['pass']));
    }
  }

  // Складываем признак ошибок в массив.
  $errors = array();
  $errors['fio'] = !empty($_COOKIE['fio_error']);

  // TODO: аналогично все поля.

  // Выдаем сообщения об ошибках.
  if (!empty($errors['fio'])) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('fio_error', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Заполните имя.</div>';
  }
  // TODO: тут выдать сообщения об ошибках в других полях.

  // Складываем предыдущие значения полей в массив, если есть.
  // При этом санитизуем все данные для безопасного отображения в браузере.
  $values = array();
  $values['fio'] = empty($_COOKIE['fio_value']) ? '' : strip_tags($_COOKIE['fio_value']);
  // TODO: аналогично все поля.

  // Если нет предыдущих ошибок ввода, есть кука сессии, начали сессию и
  // ранее в сессию записан факт успешного логина.
  if (empty($errors) && !empty($_COOKIE[session_name()]) &&
      session_start() && !empty($_SESSION['login'])) {
    // TODO: загрузить данные пользователя из БД
    // и заполнить переменную $values,
    // предварительно санитизовав.
    printf('Вход с логином %s, uid %d', $_SESSION['login'], $_SESSION['uid']);
  }

  // Включаем содержимое файла form.php.
  // В нем будут доступны переменные $messages, $errors и $values для вывода 
  // сообщений, полей с ранее заполненными данными и признаками ошибок.
  include('form.php');
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
else {
  // Проверяем ошибки.
  $errors = FALSE;
  if (empty($_POST['fio'])) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('fio_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('fio_value', $_POST['fio'], time() + 30 * 24 * 60 * 60);
  }

// *************
// TODO: тут необходимо проверить правильность заполнения всех остальных полей.
// Сохранить в Cookie признаки ошибок и значения полей.
// *************

  if ($errors) {
    // При наличии ошибок перезагружаем страницу и завершаем работу скрипта.
    header('Location: index.php');
    exit();
  }
  else {
    // Удаляем Cookies с признаками ошибок.
    setcookie('fio_error', '', 100000);
    // TODO: тут необходимо удалить остальные Cookies.
  }

  // Проверяем меняются ли ранее сохраненные данные или отправляются новые.
  if (!empty($_COOKIE[session_name()]) &&
      session_start() && !empty($_SESSION['login'])) {
    // TODO: перезаписать данные в БД новыми данными,
    // кроме логина и пароля.
  }
  else {
    // Генерируем уникальный логин и пароль.
    // TODO: сделать механизм генерации, например функциями rand(), uniquid(), md5(), substr().
    $login = '123';
    $pass = '123';
    // Сохраняем в Cookies.
    setcookie('login', $login);
    setcookie('pass', $pass);

    // TODO: Сохранение данных формы, логина и хеш md5() пароля в базу данных.
    // ...
  }

  // Сохраняем куку с признаком успешного сохранения.
  setcookie('save', '1');

  // Делаем перенаправление.
  header('Location: ./');
}

















<?php
/**
 * Реализовать проверку заполнения обязательных полей формы в предыдущей
 * с использованием Cookies, а также заполнение формы по умолчанию ранее
 * введенными значениями.
 */


function send_error_and_exit($error_message, $error_code="400"){
  header("HTTP/1.1 " . $error_code . " " . $error_message);
  exit();
}

$user = 'u53304';
$pass = '1449484';
$db = new PDO('mysql:host=localhost;dbname='.$user, $user, $pass, [PDO::ATTR_PERSISTENT => true]);
$columns = array();
$columns[] = 'full_name';
$columns[] = 'email';
$columns[] = 'birth_year';
$columns[] = 'limbs_amount';
$columns[] = 'is_male';
$columns[] = 'biography';
$columns[] = 'powers';

// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // Массив для временного хранения сообщений пользователю.
  $messages = array();

  // В суперглобальном массиве $_COOKIE PHP хранит все имена и значения куки текущего запроса.
  // Выдаем сообщение об успешном сохранении.
  if (!empty($_COOKIE['save'])) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('save', '', 100000);
    // Если есть параметр save, то выводим сообщение пользователю.
    $messages[] = 'Спасибо, результаты сохранены.';
  }

  // Складываем признак ошибок в массив.
  $errors = array();

  foreach ($columns as $column)
    $errors[$column] = !empty($_COOKIE[$column.'_error']);

  foreach ($columns as $column) // Выдаем сообщения об ошибках.
    if ($errors[$column]) {
      // Выводим сообщение.
      $messages[] = '<div class="error">'.$_COOKIE[$column.'_error'].'</div>';
      // Удаляем куку, указывая время устаревания в прошлом.
      setcookie($column.'_error', '', 100000);
    }

  // Складываем предыдущие значения полей в массив, если есть.
  $values = array();
  foreach ($columns as $column)
    $values[$column] = empty($_COOKIE[$column.'_value']) ? '' : $_COOKIE[$column.'_value'];

  // Включаем содержимое файла form.php.
  // В нем будут доступны переменные $messages, $errors и $values для вывода 
  // сообщений, полей с ранее заполненными данными и признаками ошибок.
  include('form.php');
} else { 
  // Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
  // Проверяем ошибки.
  $errors = FALSE;

  if (empty($_POST['full_name'])) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('full_name_error', 'Enter your name, please', time() + 24 * 60 * 60);
    $errors = TRUE;
  }

  if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    if (empty($_POST['email'])) 
      setcookie('email_error', 'Mail is not set', time() + 24 * 60 * 60);
    else
      setcookie('email_error', 'Mail is invalid', time() + 24 * 60 * 60);
    $errors = TRUE;
  }

  if (!isset($_POST['birth_year'])) {
    setcookie('birth_year_error', 'Year is not set', time() + 24 * 60 * 60);
    $errors = TRUE;
  }

  if (!isset($_POST['limbs_amount'])) {
    setcookie('limbs_amount_error', 'Limbs number is not set', time() + 24 * 60 * 60);
    $errors = TRUE;
  }

  if (!isset($_POST['is_male']) || ($_POST['is_male']!=0 && $_POST['is_male']!=1)) {
    if (!isset($_POST['is_male']))
      setcookie('is_male_error', 'Gender is not set', time() + 24 * 60 * 60);
    else
      setcookie('is_male_error', 'Gender is invalid', time() + 24 * 60 * 60);
    $errors = TRUE;
  }

  if (!isset($_POST['powers'])) {
    setcookie('powers_error', 'You have to choose minimum one power', time() + 24 * 60 * 60);
    $errors = TRUE;
  }

  if ($errors) {
    // При наличии ошибок перезагружаем страницу и завершаем работу скрипта.
    header('Location: index.php');
    exit();
  }
  else {
    // Удаляем Cookies с признаками ошибок.
    foreach ($columns as $column)
      setcookie($column.'_error', '', 100000);
  }


  // Сохраняем ранее введенное в форму значение на месяц.
  foreach ($columns as $column)
    setcookie($column, $_POST[$column], time() + 30 * 24 * 60 * 60);
  

  // Сохраняем в бд
  try {
    $stmt = $db->prepare(
      "INSERT INTO Person ".
      "(full_name, email, birth_year, is_male, limbs_amount, biography) ".
      "VALUES (:full_name, :email, :birth_year, :is_male, :limbs_amount, :biography);"
      );
    $stmtErr =  $stmt -> execute(
          [
          'full_name' => $_POST['full_name'],
          'email' => $_POST['email'] , 
          'birth_year' => $_POST['birth_year'], 
          'is_male' => $_POST['is_male'], 
          'limbs_amount' => $_POST['limbs_amount'], 
          'biography' => $_POST['biography']
          ]
      );
    if (!$stmtErr) 
      send_error_and_exit("Some server issue","500");
    $strId = $db->lastInsertId();
    
    foreach ($_POST['powers'] as $item) {
      $stmt = $db->prepare(
        "INSERT INTO Person_Ability (person_id, ability_id) VALUES (:p, :a);"
      );
      $stmtErr = $stmt->execute(['p' => intval($strId), 'a' => $item]);
      if (!$stmtErr)
        send_error_and_exit("Some server issue","500");
    }
    
  }
  catch(PDOException $e){
      send_error_and_exit("Some server issue","500");
  }

  // Сохраняем куку с признаком успешного сохранения.
  setcookie('save', '1');

  // Делаем перенаправление.
  header('Location: index.php');
}
