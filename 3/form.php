<?php

include 'includes.php';




if (empty($_POST['full_name'])) 
    send_error_and_exit("Enter your name, please");
if (empty($_POST['email']) 
    || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
    send_error_and_exit("Mail is not set or is invalid");
if (!isset($_POST['birth_year']))
    send_error_and_exit("Year is not set");
if (!isset($_POST['limbs_amount'])) 
    send_error_and_exit("Limbs number is not set");
if (!isset($_POST['is_male']) 
    || ($_POST['is_male']!=0 && $_POST['is_male']!=1))
    send_error_and_exit("Gender is not set or is invalid");




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
  if (isset($_POST['powers'])) {
        foreach ($_POST['powers'] as $item) {
            $stmt = $db->prepare(
                "INSERT INTO Person_Ability (person_id, ability_id) VALUES (:p, :a);"
            );
            $stmtErr = $stmt->execute(['p' => intval($strId), 'a' => $item]);
            if (!$stmtErr)
                send_error_and_exit("Some server issue","500");
        }
  }
}
catch(PDOException $e){
    send_error_and_exit("Some server issue","500");
}