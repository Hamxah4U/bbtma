<?php
require 'Database.php';
session_start();
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $errors = [];
    $success = [];
    $session = $_POST['session'];
    $term = $_POST['term'];
    $stdclass = $_POST['stdclass'];
    $studentregno = $_POST['stidentregno'];
    
    if(empty(trim($studentregno))){
        $errors['studentregno'] = 'Registration number is required!';
    }
    if($stdclass == '--choose--'){
        $errors['class'] = 'Class is required!';
    }
    if($session == '--choose--'){
        $errors['session'] = 'Academic session is required!';
    }
    if($term == '--choose--'){
        $errors['term'] = 'Academic term is required!';
    }
    
    if(empty($errors)){
        if (strtolower($studentregno) == 'all') {
            $sql = $db->conn->prepare('SELECT * FROM `score_tbl` WHERE `stdclass` = :stdclass
              AND `session` = :session 
              AND `term` = :term');
            $sql->execute([
                ':stdclass' => $stdclass,
                ':session' => $session,
                ':term' => $term
            ]);
            $result = $sql->fetchAll(PDO::FETCH_ASSOC);
        
            if (count($result) > 0) {
                $_SESSION['report_result'] = $result;
                $success['redirect'] = '/allresult';
                echo json_encode([
                    'status' => true,
                    'success' => $success
                ]);
                exit;
            } else {
                $errors['studentregno'] = 'No records found!';
            }
        } else {
            $stmt = $db->conn->prepare('SELECT * 
                FROM `score_tbl`
                WHERE `stdclass` = :stdclass 
                  AND `Reg_no` = :Reg_no 
                  AND `session` = :session 
                  AND `term` = :term');
            $stmt->execute([
                ':stdclass' => $stdclass,
                ':Reg_no' => $studentregno,
                ':session' => $session,
                ':term' => $term
            ]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            if (count($result) > 0) {
                $_SESSION['report_result'] = $result; 
                $success['redirect'] = '/printresult';
                echo json_encode([
                    'status' => true,
                    'success' => $success
                ]);
                exit;
            } else {
                $errors['studentregno'] = 'No records found!';
            }
        }
        
        if (count($errors) > 0) {
            echo json_encode([
                'status' => false,
                'errors' => $errors
            ]);
        }
    }
}
?>