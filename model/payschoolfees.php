<?php
  require 'Database.php';

  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $cr = $_POST['cr'];
    $narr = $_POST['narr'];
    $stdid = $_POST['stdid'];
    $success = [];
    $errors = [];

    if(empty(trim($cr))){
      $errors['cr'] = 'Required!';
    }

    if(empty(trim($narr))){
      $errors['narr'] = 'Required!';
    }

    if(empty($errors)){
      session_start();
      $stmt = $db->conn->prepare("INSERT INTO `schoolfees_tbl`(std_ID,CreditSide,Narration,Cashby,userID) VALUES (:std_ID, :CreditSide, :Narration,  :Cashby, :userID)");
      $stmt->execute([
        ':std_ID' => $stdid,
        ':CreditSide' => $cr,
        ':Narration' => $narr,
        ':Cashby' => $_SESSION['email'],
        ':userID' => $_SESSION['userID']
      ]);

    }

    if(count($errors) > 0){
      echo json_encode([
        'status' => false,
        'errors' => $errors
      ]);
    }else{
      echo json_encode([
        'status' => true,
        'success' => $success
      ]);
    }

  }
?>