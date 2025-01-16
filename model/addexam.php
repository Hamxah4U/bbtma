<?php
require 'Database.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
  $errors = [];
  $success = [];
  session_start();

  $classandsubject = $_POST['classandsubject'];
  $stuid = $_POST['stuid'] ?? [];
  $regno = $_POST['regno'] ?? [];
  $ftest = $_POST['ftest'] ?? [];
  $stest = $_POST['stest'] ?? [];
  $rtest = $_POST['rtest'] ?? [];
  $exam = $_POST['exam'] ?? [];
  $session = $_POST['session'] ?? '';
  $term = $_POST['term'] ?? '';
  $class = $_POST['class'] ?? '';
  $subject = $_POST['subject'] ?? '';

  if($classandsubject === '--choose--'){
    $errors['classandsubject'] = 'Please select class and subject!';
  }

  if(empty($stuid)){
    $errors['students'] = 'No student records to process.';
  }

  if(empty($errors)){
    try{
      $db->conn->beginTransaction();

      foreach ($stuid as $index => $studentID) {
        $reg = $regno[$index] ?? '';
        $first = $ftest[$index] ?? 0;
        $second = $stest[$index] ?? 0;
        $third = $rtest[$index] ?? 0;
        $examScore = $exam[$index] ?? 0;

        // score record  exists
        $checkStmt = $db->conn->prepare("SELECT id FROM score_tbl WHERE stdID = :stdID AND stdclass = :stdclass AND `subject` = :subject AND `session` = :session AND term = :term");
        $checkStmt->execute([
            ':stdID' => $studentID,
            ':stdclass' => $class,
            ':subject' => $subject,
            ':session' => $session,
            ':term' => $term,
        ]);

        if($checkStmt->rowCount() > 0){
          $updateStmt = $db->conn->prepare("UPDATE score_tbl SET `first` = :first, `second` = :second, third = :third, exam = :exam, updateby = :updateby, updatedate = NOW() 
            WHERE stdID = :stdID AND stdclass = :stdclass AND `subject` = :subject AND `session` = :session AND term = :term");
          $updateStmt->execute([
            ':first' => $first,
            ':second' => $second,
            ':third' => $third,
            ':exam' => $examScore,
            ':updateby' => $_SESSION['email'],
            ':stdID' => $studentID,
            ':stdclass' => $class,
            ':subject' => $subject,
            ':session' => $session,
            ':term' => $term,
          ]);
        }else{
          // Insert score
          $insertStmt = $db->conn->prepare(
            "INSERT INTO score_tbl (stdID, Reg_no, stdclass, subject, first, second, third, exam, session, term, added_by, TID) 
            VALUES (:stdID, :Reg_no, :stdclass, :subject, :first, :second, :third, :exam, :session, :term, :added_by, :TID)");
          $insertStmt->execute([
            ':stdID' => $studentID,
            ':Reg_no' => $reg,
            ':stdclass' => $class,
            ':subject' => $subject,
            ':first' => $first,
            ':second' => $second,
            ':third' => $third,
            ':exam' => $examScore,
            ':session' => $session,
            ':term' => $term,
            ':added_by' => $_SESSION['email'],
            ':TID' => $_SESSION['userID']
          ]);
        }
      }
        $db->conn->commit();
        $success['message'] = 'Student scores have been successfully saved.';
        echo json_encode(['status' => true, 'success' => $success]);
    }catch(Exception $e){
      $db->conn->rollBack();
      $errors['database'] = 'An error occurred while saving the scores: ' . $e->getMessage();
      echo json_encode(['status' => false, 'errors' => $errors]);
    }
  }else{
    echo json_encode(['status' => false, 'errors' => $errors]);
  }
}
?>