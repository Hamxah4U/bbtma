<?php
require 'Database.php';

header('Content-Type: application/json'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $classp = $_POST['category'];
    $cclass = $_POST['cclass'];
    $students = $_POST['myID'] ?? [];
    $regno = $_POST['regno'] ?? [];
    $attendance = $_POST['attendance'] ?? [];
    $stdclass = $_POST['stdclass'] ?? [];
    $term = $_POST['term'] ?? [];
    $session = $_POST['session'] ?? [];
    $category = $_POST['category'] ?? [];
    $errors = [];
    $success = [];

    $attendanceErrors = [];
    foreach ($attendance as $key => $attend) {
        if ($attend == '--select--') {
            $attendanceErrors[$key] = true;
        }
    }

    if (!empty($attendanceErrors)) {
        $errors['attendance'] = 'Required!';
    }

    if ($cclass == '--choose--') {
        $errors['cclass'] = 'Please select a class!';
    }
    if ($classp == '--choose--') {
        $errors['classp'] = 'Please select category!';
    }

    if (empty($students)) {
        $errors['students'] = 'No students selected!';
    }

    
    if(empty($errors)){
        session_start();
        $sql = $db->conn->prepare('INSERT INTO `attendance_tbl` (`stid`, `regno`,`mark`, `class`, `term`, `session`, `created_at`, `created_at_time`, `created_by`, `category`) VALUES (:stid, :regno, :mark, :class, :term, :session, CURRENT_DATE(), CURRENT_TIME(), :created_by, :category ) ');
        $result = false; 
        foreach ($students as $key => $studentId) {
            $stmtexist = $db->conn->prepare('SELECT * FROM attendance_tbl WHERE stid=:stid AND class=:class AND term=:term AND `session`=:session AND category=:category');
            $stmtexist->execute([
                ':stid' => $studentId,
                ':class' => $stdclass[$key],
                ':term' => $term[$key],
                ':session' => $session[$key],
                ':category' => $classp
            ]);

            // if ($stmtexist->rowCount() > 0) {
            //     $sql = $db->conn->prepare('UPDATE attendance_tbl SET mark=:mark, created_at=CURRENT_DATE(), created_at_time=CURRENT_TIME(), created_by=:created_by WHERE stid=:stid AND class=:class AND term=:term AND `session`=:session AND category=:category');
            // } else {
            //     $sql = $db->conn->prepare('INSERT INTO attendance_tbl (stid, regno, mark, class, term, session, created_at, created_at_time, created_by, category) VALUES (:stid, :regno, :mark, :class, :term, :session, CURRENT_DATE(), CURRENT_TIME(), :created_by, :category)');
            // }



          $result = $sql->execute([
            ':stid' => $studentId,
            ':regno' => $regno,
            'mark' => $attendance[$key],
            ':class' => $stdclass[$key],
            ':term' => $term[$key],
            ':session' => $session[$key],
            ':created_by' => $_SESSION['email'],
            ':category' => $classp
          ]);
        }
        
        if($result){
            $success['message'] = 'Attendance taken successfully!';
        }
    }
    

    if(count($errors) > 0){
        echo json_encode([
            'status' => false,
            'errors' => $errors,
            'attendanceErrors' => $attendanceErrors
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
