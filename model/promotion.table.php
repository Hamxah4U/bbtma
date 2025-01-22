<?php
 
  require 'Database.php';

  if (isset($_POST['class'])) {
    $class = $_POST['class'];

    $stmt = $db->conn->prepare('SELECT * FROM `student_tbl` 
    JOIN gender_tbl ON gender_tbl.id = student_tbl.Gender
    JOIN class_tbl ON student_tbl.Class = class_tbl.class_ID 
    WHERE `Class` = :class  ');
    $stmt->bindParam(':class', $class, PDO::PARAM_STR);
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($students);
} else {
  echo json_encode(['error' => 'Class not selected']);
}

?>