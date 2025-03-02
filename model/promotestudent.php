<?php
require 'Database.php';

header('Content-Type: application/json'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $classp = $_POST['classp'];
    $cclass = $_POST['cclass'];
    $students = $_POST['myID'] ?? [];
    $errors = [];
    $success = [];

    if ($cclass == '--choose--') {
        $errors['cclass'] = 'Please select a class!';
    }
    if ($classp == '--choose--') {
        $errors['classp'] = 'Please select a promotion class!';
    }

    if (empty($students)) {
        $errors['students'] = 'No students selected!';
    }
       
    if(empty($errors)){
        session_start();
        $sql = $db->conn->prepare('UPDATE `student_tbl` SET `Class` = :class, `PromotedBy` = :PromotedBy, PromotionDate = CURRENT_DATE() WHERE `stu_ID` = :stu_id');
        $result = false; 
        foreach ($students as $studentId) {
            $result = $sql->execute([
                ':class' => $classp,
                ':stu_id' => $studentId,
                ':PromotedBy' => $_SESSION['email']
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
    

   
}
?>
