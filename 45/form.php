<html>
  <head>
    <style>
      /* Сообщения об ошибках и поля с ошибками выводим с красным бордюром. */
      .error {
        border: 2px solid red;
      }
    </style>
  </head>
  <body>

<?php
if (!empty($messages)) {
  print('<div id="messages">');
  // Выводим все сообщения.
  foreach ($messages as $message) {
    print($message);
  }
  print('</div>');
}

// Далее выводим форму отмечая элементы с ошибками классом error
// и задавая начальные значения элементов ранее сохраненными.
?>

  <form action="" method="POST">
              <label  <?php if ($errors['full_name']) {print 'class="error"';} ?>>
                Имя:<br>
                <input name="full_name"
                  value="<?php print $values['fullname']; ?>"
                  placeholder="Имя" required>
              </label><br>
        
              <label <?php if ($errors['email']) {print 'class="error"';} ?>>
                E-mail:<br>
                <input name="email"
                  type="email"
                  value="<?php print $values['email']; ?>"
                  placeholder="e-mail" required>
              </label><br>
        
              <label <?php if ($errors['birth_year']) {print 'class="error"';} ?>>
                Год рождения:<br>
                <select name="birth_year">
                    <?php 
                      for ($i = 1923; $i <= 2023; $i++) {
                        if ($values['birth_year'] == $i)
                          printf('<option value="%d" selected="selected">%d год</option>', $i, $i);
                        else
                          printf('<option value="%d">%d год</option>', $i, $i);
                      }
                    ?>
                  </select>
              </label><br>
              
              <div <?php if ($errors['is_male']) {print 'class="error"';} ?>>
                Пол: <br>
                <label><input type="radio"
                  name="is_male" value="1" <?php if ($values['is_male'] == 1) print "checked"; ?> required>
                  Мужской</label>
                <label><input type="radio"
                  name="is_male" value="0" <?php if ($values['is_male'] == 0) print "checked"; ?> required>
                  Женский</label><br>
              </div>

              <div <?php if ($errors['limbs_amount']) {print 'class="error"';} ?>>
                Количество конечностей: <br>

                <?php 
                  for ($i = 1; $i <= 4; $i++) {
                    if ()
                    print '<label><input type="radio" name="limbs_amount" value="'.$i.'"'..' required>'.$i.'</label>';
                  }
                ?>
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
              </div>


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
        
              <label <?php if ($errors['biography']) {print 'class="error"';} ?>>
                Биография:<br>
                <textarea name="biography"></textarea>
              </label><br>
        
              Согласие c лицензионным соглашением:<br>
              <label><input type="checkbox"
                name="check" required>
                Да</label><br>
        
              <input type="submit" value="Отправить">
            </form>

            <table>
            <?php
                   try {
                      foreach ($db->query("SELECT * FROM Person;") as $person){
                        $abilities = '      ';
                        foreach ($db->query('SELECT * FROM Person_Ability WHERE person_id='.intval($person['id']).';') as $pa){
                          foreach ($db->query('SELECT _name FROM Ability WHERE id='.intval($pa['ability_id']).';') as $a){
                            $abilities = $abilities.'     '.$a['_name'];
                          }
                        }
                        print '<tr><td>'.$person['full_name'].' ::::: </td><td>'.$abilities.'</td></tr>';
                      }
                    } catch(PDOException $e){
                      send_error_and_exit("Db connection error", "500");
                    }
             ?>
            </table>
    <!--
    <form action="" method="POST">
      <input name="fio" <?php if ($errors['fio']) {print 'class="error"';} ?> value="<?php print $values['fio']; ?>" />
      <input type="submit" value="ok" />
    </form>
                  -->
  </body>
</html>
