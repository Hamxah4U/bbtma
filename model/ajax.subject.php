<?php
  require 'Database.php';
  if (isset($_POST['class_ID'])) {
    $classID = $_POST['class_ID'];

    $query = $db->conn->prepare("SELECT sub_ID, Subject_name FROM subject_tbl WHERE class_ID = :classID");
    $query->bindParam(':classID', $classID, PDO::PARAM_INT);
    $query->execute();

    $subjects = $query->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($subjects);
}
?>