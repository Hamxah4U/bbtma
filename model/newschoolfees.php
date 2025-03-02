<?php
require 'Database.php';

header('Content-Type: application/json'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cclass = $_POST['cclass'] ?? [];
    $amountfee = $_POST['amountfee'];
    $students = $_POST['myID'] ?? [];
    $stdclass = $_POST['stdclass'] ?? [];
    $session = $_POST['session'] ?? [];
    $term = $_POST['term'] ?? [];
    $errors = [];
    $success = [];

    if($cclass == '--choose--') {
      $errors['cclass'] = 'Please select a class!';
    }
    
    if(empty($students)) {
      $errors['students'] = 'No students selected!';
    }

    if(empty(trim($amountfee))){
      $errors['amountfee'] = 'School fees amount is required!';
    }

    if(empty($errors)){
      session_start();
      $result = false;

      foreach ($students as $key => $studentId) {   
        $stmtexist = $db->query("SELECT * FROM schoolfees_tbl WHERE stdID='$studentId' AND `session`='{$session[$key]}' AND term='{$term[$key]}' AND `class`='{$stdclass[$key]}'");
        if($stmtexist->rowCount() > 0){
          $errors['amountfee'] = 'School fees already assigned!';
          continue; // Skip this student and move to the next one
        }
          /* $stmtexist = $db->conn->prepare('SELECT * FROM schoolfees_tbl WHERE stdID=:stdID AND `session`=:session AND term=:term AND `class`=:class');
          $stmtexist->execute([
              ':stdID' => $studentId,
              ':session' => $session[$key],
              ':term' => $term[$key],
              ':class' => $stdclass[$key]
          ]);    

          if($stmtexist->rowCount() > 0){
            $errors['amountfee'] = 'School fees already assigned';// for student ID ' . $studentId;
            break;
          } */

          $sql = $db->conn->prepare('INSERT INTO schoolfees_tbl (stdID, fees, `session`, term, `class`, created_at, Cashby, userID) VALUES (:stdID, :fees, :session, :term, :class, CURRENT_DATE(), :Cashby, :userID)');
          $result = $sql->execute([
              ':stdID' => $studentId,
              ':fees' => $amountfee,
              ':session' => $session[$key],
              ':term' => $term[$key],
              ':class' => $stdclass[$key],
              ':Cashby' => $_SESSION['email'],
              ':userID' => $_SESSION['userID']
          ]);
      }
      
      if($result && empty($errors)){
        $success['message'] = 'School fees assigned successfully!';
      }
    } 
    
    if(count($errors) > 0){
        echo json_encode([
            'status' => false,
            'errors' => $errors
        ]);
    } else {
        echo json_encode([
            'status' => true,
            'message' => 'School fees assigned successfully!',
            'success' => $success
        ]);
    }   
}
?>


<?php
/* require 'Database.php';

header('Content-Type: application/json'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cclass = $_POST['cclass'] ?? [];
    $amountfee = $_POST['amountfee'];
    $students = $_POST['myID'] ?? [];
    $stdclass = $_POST['stdclass'] ?? [];
    $session = $_POST['session'] ?? [];
    $term = $_POST['term'] ?? [];
    $errors = [];
    $success = [];

    if($cclass == '--choose--') {
      $errors['cclass'] = 'Please select a class!';
    }
    
    if(empty($students)) {
      $errors['students'] = 'No students selected!';
    }

    if(empty(trim($amountfee))){
      $errors['amountfee'] = 'School fees amount is required!';
    }


    $stmtexist = $db->conn->prepare('SELECT * FROM schoolfees_tbl WHERE stdID=:stdID AND `session`=:session AND term=:term AND `class`=:class');
        $stmtexist->execute([
            ':stdID' => $studentId,
            ':session' => $session[$key],
            ':term' => $term[$key],
            ':class' => $stdclass[$key]
        ]);    
    
        if($stmtexist->rowCount() > 0){
          $errors['amountfee'] = 'School fees already assign!';
        }

        
    if(empty($errors)){
      session_start();
      $sql = $db->conn->prepare('INSERT INTO `schoolfees_tbl` (stdID, fees, `session`, term, `class`, created_at, Cashby, userID) VALUES (:stdID, :fees, :session, :term, :class, CURRENT_DATE(), :Cashby, :userID) '); 
      foreach ($students as $key => $studentId) {
        $result = $sql->execute([
          ':stdID' => $studentId,
          ':fees' => $amountfee,
          ':session' => $session[$key],
          ':term' => $term[$key],
          ':class' => $stdclass[$key],
          ':Cashby' => $_SESSION['email'],
          ':userID' => $_SESSION['userID']
        ]);
      }
      
      if($result){
        $success['message'] = 'Students promoted successfully!';
      }
    } 
    

    if(count($errors) > 0){
        echo json_encode([
            'status' => false,
            'errors' => $errors
        ]);
    }else{
        echo json_encode([
            'status' => true,
            'message' => 'Students promoted successfully!',
            'success' => $success
        ]);
    }
    

   
} */
?>
