<html>
<head>
  <style>
    td{
      margin: 10px;
      border: 2px solid black;
    }
    </style>
</head>
<body>

<?php
include_once 'includes.php';
/**
 * Задача 6. Реализовать вход администратора с использованием
 * HTTP-авторизации для просмотра и удаления результатов.
 **/

// Пример HTTP-аутентификации.
// PHP хранит логин и пароль в суперглобальном массиве $_SERVER.
// Подробнее см. стр. 26 и 99 в учебном пособии Веб-программирование и веб-сервисы.
if (empty($_SERVER['PHP_AUTH_USER']) ||
    empty($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] != 'admin' ||
    md5($_SERVER['PHP_AUTH_PW']) != md5('123')) {
  header('HTTP/1.1 401 Unanthorized');
  header('WWW-Authenticate: Basic realm="My site"');
  print('<h1>401 Требуется авторизация</h1>');
  exit();
}

print('Вы успешно авторизовались и видите защищенные паролем данные.');

// *********
// Здесь нужно прочитать отправленные ранее пользователями данные и вывести в таблицу.
// Реализовать просмотр и удаление всех данных.
// *********

print '<br>';

print 'Статистика: <br>';
print '<table>';
print '<tr><th>Способность</th><th>Число тех, у кого она есть</th></tr>';
foreach (
  $db->query('SELECT a._name AS nm, count(*) AS amnt FROM Ability AS a'.
  ' JOIN Person_Ability AS pa ON a.id=pa.ability_id'.
  ' JOIN Person AS p ON p.id=pa.person_id'.
  ' GROUP BY a._name;') as $row
  ){
    print '<tr> <td>'.$row['nm'].'</td> <td>'.$row['amnt'].'</td> </tr>';
}
print '</table>';
print '<br>';

print '<table>';

try {
  foreach ($db->query("SELECT * FROM Person;") as $person){
    $abilities = '';
    foreach ($db->query('SELECT * FROM Person_Ability WHERE person_id='.intval($person['id']).';') as $pa){
      foreach ($db->query('SELECT _name FROM Ability WHERE id='.intval($pa['ability_id']).';') as $a){
        $abilities = $abilities.$a['_name'].', ';
      }
    }
    print '<tr><td>'.$person['full_name'].'</td><td>'.$person['email'].'</td><td>'.$person['birth_year'].'</td><td>'.$person['is_male'].'</td><td>'.$person['limbs_amount'].'</td><td>'.$person['biography'].'</td><td>'.$abilities.'</td></tr>';
  }
} catch(PDOException $e){
  send_error_and_exit("Db connection error", "500");
}

print '</table>';

?>

<body>
<html>