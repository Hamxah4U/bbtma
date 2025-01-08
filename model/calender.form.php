<?php
    require 'Database.php';

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $newsession = $_POST['newsession'];
      $newterm = $_POST['newterm'];
      $errors = [];
      $success = [];

      if($newsession == '--choose--'){
        $errors['newsession'] = 'Session is required!';
      }

      if($newterm == '--choose--'){
        $errors['newterm'] = 'Term is required!';
      }

      if(empty($errors)){
        $stmt = $db->conn->prepare('UPDATE `student_tbl` SET `Session` = :Session, `Term` = :Term WHERE `stu_ID` > 0');
        $stmt->bindParam(':Session', $newsession);
        $stmt->bindParam(':Term', $newterm);
        $result = $stmt->execute();
        if($result){
          $success['message'] = 'Academic calendar set successfully!';
        }
      }

      if(count($errors) > 0){
        echo json_encode([
          'status' => false,
          'errors' => $errors,
        ]);
      }else{
        echo json_encode([
          'status' => true,
          'success' => $success
        ]);
      }
    }
?>