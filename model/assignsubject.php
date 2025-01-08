<?php
  require 'Database.php';

 if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $class = $_POST['class'];
  $subjectuser = $_POST['subjectuser'];
  $userID = $_POST['userID'];
  $errors = [];
  $success = [];

  $stmtexist = $db->checkExist("SELECT * FROM `usersubject_tbl` WHERE `class` = '$class' AND `subject` = '$subjectuser'   ");
  $stmtexist->execute();
  $rowexist = $stmtexist->rowCount();


  if($subjectuser == '--choose--'){
    $errors['subjectuser'] = 'Subject is required!';
  }

  if($class == '--choose--'){
    $errors['class'] = 'Class is required!';
  }elseif($rowexist > 0){
    $errors['class'] = 'The class and the subject already taken!';
  }

  if(empty($errors)){
    session_start();
    $Added_by = $_SESSION['email'];
    $stmt = $db->conn->prepare('INSERT INTO usersubject_tbl (`userID`,class,`subject`,Added_by) VALUES (:userID,:class,:subject,:Added_by) ');
    $stmt->bindParam(':userID', $userID);
    $stmt->bindParam(':class', $class);
    $stmt->bindParam(':subject', $subjectuser);
    $stmt->bindParam(':Added_by', $Added_by);

    $result = $stmt->execute();
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