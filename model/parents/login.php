<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../Database.php';
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $errors = [];
    $success = [];
    $db = new Database();
    $conn = $db->conn;
    $studentUsername = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    if(empty(trim($password))){
        $errors['password_error'] = 'Required!';
    }

    if(empty(trim($studentUsername))){
        $errors['username_error'] = 'Required!';
    }

    if(empty($errors)){
        $status = 'Active';
        $stmt = $conn->prepare('SELECT * FROM `student_tbl` WHERE `Status` = :userstatus AND `Reg_no` = :Reg_no OR `Phone` = :Phone OR `Email` = :email');
        $stmt->execute(['Reg_no' => $studentUsername, 'Phone' => $studentUsername, 'email' => $studentUsername, 'userstatus' => $status]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        if($student){
            $stdEmail = $student['Email'];
            $stdReg = $student['Reg_no'];
            $stdPhone = $student['Phone'];
            $fname = $student['FirstName'];
            $sname = $student['Surname'];
            $mname = $student['OtherName'];
            $gender = $student['Gender'];
            $img = $student['Passphort'];
            $stdPassword = $student['Password'];
            $id = $student['stu_ID'];
            if (password_verify($password, $stdPassword)) {
                $_SESSION['email'] = $stdEmail;
                $_SESSION['reg'] = $stdReg;
                $_SESSION['phone'] = $stdPhone;
                $_SESSION['fname'] = $fname;
                $_SESSION['mname'] = $mname;
                $_SESSION['sname'] = $sname;
                $_SESSION['img'] = $img;
                $_SESSION['userID'] = $id;

                $success['message'] = 'Login successful, please wait...';
                $success['redirect'] = '/parentsdashboard'; 
            }else{
                $errors['password_error'] = 'Invalid Password!';
            }
        }else{
            $errors['username_error'] = 'Email or Phone or Student ID does not exist!';
        }
    }

    header('Content-Type: application/json');
    if(count($errors) > 0){
        echo json_encode([
            'status' => false,
            'errors' => $errors
        ]);
    } else {
        echo json_encode([
            'status' => true,
            'success' => $success,
        ]);
    }
    exit();
}
?>
