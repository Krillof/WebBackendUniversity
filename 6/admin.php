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
if (!is_admin($db)) {
  header('HTTP/1.1 401 Unanthorized');
  header('WWW-Authenticate: Basic realm="My site"');
  print('<h1>401 Требуется авторизация</h1>');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>
<h1> Панель администратора </h1>
Вы успешно авторизовались и видите защищенные паролем данные.
<br>

<h2>Статистика:</h2> <br>

<table>
<tr><th>Способность</th><th>Число тех, у кого она есть</th></tr>

<?php
  foreach (
    $db->query('SELECT a._name AS nm, count(*) AS amnt FROM Ability AS a'.
    ' JOIN Person_Ability AS pa ON a.id=pa.ability_id'.
    ' JOIN Person AS p ON p.id=pa.person_id'.
    ' GROUP BY a._name;') as $row
    ){
      print '<tr> <td>'.$row['nm'].'</td> <td>'.$row['amnt'].'</td> </tr>';
  }
?>
</table>
<br>
<h2>Пользователи:</h2> <br>
<table>
<tr><th>Имя</th><th>Почта</th><th>Год рождения</th><th>Мужчина?</th><th>Число конечностей</th><th>Биография</th><th>Способности</th></tr>
<?php
  try {
    foreach ($db->query("SELECT * FROM Person;") as $person){
      $abilities = '';
      foreach ($db->query('SELECT * FROM Person_Ability WHERE person_id='.intval($person['id']).';') as $pa){
        foreach ($db->query('SELECT _name FROM Ability WHERE id='.intval($pa['ability_id']).';') as $a){
          $abilities = $abilities.$a['_name'].', ';
        }
      }
      $delete_user_button='<td><form action="admin.php" method="POST"> <input hidden name="type" type="text" value="delete"/> <input hidden name="user" type="text" value="'.$person['id'].'"/> <button> DELETE </button> </form></td>';
      $change_user_button='<td><form action="admin.php" method="POST"> <input hidden name="type" type="text" value="change"/> <input hidden name="user" type="text" value="'.$person['id'].'"/> <button> CHANGE </button> </form></td>';
      $current_user_info='<td>'.$person['full_name'].'</td><td>'.$person['email'].'</td><td>'.$person['birth_year'].'</td><td>'.$person['is_male'].'</td><td>'.$person['limbs_amount'].'</td><td>'.$person['biography'].'</td><td>'.$abilities.'</td>';
      print '<tr>'.$current_user_info.$delete_user_button.$change_user_button.'</tr>';
    }
  } catch(PDOException $e) {
    send_error_and_exit("Db connection error", "500");
  }

  print '</table>';


} else {
  // If request method was POST then

  if (!empty($_POST['user'])) {
    $id = $_POST['user'];
    if ($_POST['type'] == 'delete'){
      $db->query('DELETE FROM Person_Ability WHERE person_id='.$id.';');
      $db->query('DELETE FROM Person WHERE id='.$id.';');
    } else if ($_POST['type'] == 'change') {
      $_SERVER['ADMIN_IS_LOOKING_AT_THIS_USER'] = $id;
      header('Location: ./');
    } else {
      ?> 
      <div class="error"> Unknown type </div>
      <?php
    }
  }

}
?>

<body>
<html>