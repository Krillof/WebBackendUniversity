<!DOCTYPE html>

<?php

include 'includes.php';

?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Задание 3</title>
        <link rel="stylesheet" href="style.css">
        <meta name="viewport" content="width=device-width initial-scale=1"> 
        <script src="script.js" defer></script>
    </head>
    <body>
        <form action="form.php" method="POST">
            <label>
                Имя:<br>
                <input name="full_name"
                  placeholder="Имя" required>
              </label><br>
        
              <label>
                E-mail:<br>
                <input name="email"
                  type="email"
                  placeholder="e-mail" required>
              </label><br>
        
              <label>
                Год рождения:<br>
                <select name="birth_year">
                    <?php 
                      for ($i = 1923; $i <= 2023; $i++) {
                        printf('<option value="%d">%d год</option>', $i, $i);
                      }
                    ?>
                  </select>
              </label><br>
              
              Пол: <br>
              <label><input type="radio"
                name="is_male" value="1" checked required>
                Мужской</label>
              <label><input type="radio"
                name="is_male" value="0" required>
                Женский</label><br>
        
              Количество конечностей: <br>
              <label><input type="radio"
                name="limbs_amount" value="1" required>
                1</label>
              <label><input type="radio"
                name="limbs_amount" value="2" required>
                2</label>
              <label><input type="radio"
                name="limbs_amount" value="3" required>
                3</label>
              <label><input type="radio" 
                name="limbs_amount" value="4" checked required>
                4</label><br>
        
            <label>
                Суперсилы:
                <br>
                <select name="powers[]"
                  multiple="multiple">
                  <?php
                    try {
                      foreach ($db->query("SELECT * FROM Ability;") as $row){
                        print '<option value="'.intval($row['id']).'">'.$row['_name'].'</option>';
                      }
                    } catch(PDOException $e){
                      send_error_and_exit("Db connection error", "500");
                    }
                  ?>
                </select>
              </label><br>
        
              <label>
                Биография:<br>
                <textarea name="biography"></textarea>
              </label><br>
        
              Согласие c лицензионным соглашением:<br>
              <label><input type="checkbox"
                name="check" required>
                Да</label><br>
        
              <input type="submit" value="Отправить">
            </form>

            <br>
            <img src="1.PNG">
            <br>
            <img src="2.PNG">
    </body>
</html>