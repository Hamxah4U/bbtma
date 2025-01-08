<?php
  require '../Database.php';

  if($_POST['class_id']){
    $class_id = $_POST['class_id'];
    $stmtcode = $db->conn->prepare('SELECT `stu_ID` FROM `student_tbl` ORDER BY `stu_ID` DESC');
    $stmtcode->execute();
    $result = $stmtcode->fetch(PDO::FETCH_ASSOC);
    
    $num = $result ? $result['stu_ID'] + 1 : 1;
    $date = date('y');
    $ds = str_pad($num, 4, "0", STR_PAD_LEFT);
    $regno = "$date/$ds/";

    $queryClass = $db->conn->prepare("SELECT `code` FROM `class_tbl` WHERE `class_ID` = :class_id");
    $queryClass->bindParam(':class_id', $class_id, PDO::PARAM_INT);
    $queryClass->execute();
    $classResult = $queryClass->fetch(PDO::FETCH_ASSOC);
    if ($classResult) {
      echo $regno.$classResult['code'];
    } else {
      echo "Class code not found.";
    }
  }else{
    echo 'invalid request';
  }

 