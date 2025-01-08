<?php
  require 'Database.php';

  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $subject = $_POST['subject'];
    $regno = $_POST['regno'];
    $ftest = $_POST['ftest'];
    $stest = $_POST['stest'];
    $rtest = $_POST['rtest'];
    $exam = $_POST['exam'];
    $success = [];
    $errors = [];

    if($subject == '--choose--'){
      $errors['subject'] = 'Subject is required!';
    }

    if (count($regno) != count($ftest) || count($regno) != count($stest) || count($regno) != count($rtest) || count($regno) != count($exam)) {
      $errors['general'] = 'Input arrays are mismatched!';
    }

    $stmt = $conn->prepare("INSERT INTO score_tbl (Reg_no, subject, first, second, third, exam)
            VALUES (:Reg_no, :subject, :first, :second, :third, :exam) ");
    
    for ($i = 0; $i < count($regno); $i++) {
      $stmt->execute([
          ':Reg_no' => $regno[$i],
          ':subject' => $subject,
          ':first' => $ftest[$i],
          ':second' => $stest[$i],
          ':third' => $rtest[$i],
          ':exam' => $exam[$i],
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