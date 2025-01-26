<?php
require 'Database.php';
ob_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $db = new Database();
  $conn = $db->conn;
  $errors = [];
  $success = [];
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  if(empty($email)) {
    $errors['email'] = "Email or Phone is required!";
  }
  if(empty($password)) {
    $errors['password'] = "Password is required!";
  }

  if(empty($errors)){
    $status = 'Active';
    $stmt = $conn->prepare('SELECT `Address`, `Gender`, `Role`, `Email`, `Phone`, `UserPassword`, `user_ID`, `Fullname`  FROM `users_tbl` WHERE `Status` = :userstatus AND `Email` = :email OR `Phone` = :phone');
    $stmt->execute(['email' => $email, 'phone' => $email, 'userstatus' => $status]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user) {
      $userEmail = $user['Email'];
      $userPhone = $user['Phone'];
      $userPassword = $user['UserPassword'];
      $userID = $user['user_ID'];
      $fname = $user['Fullname'];
      $role = $user['Role'];
      $gender = $user['Gender'];
      $address = $user['Address'];
      
      if(password_verify($password, $userPassword)){
        session_start();
        $_SESSION['address'] = $address;
        $_SESSION['gender'] = $gender;
        $_SESSION['email'] = $userEmail;
        $_SESSION['phone'] = $userPhone;
        $_SESSION['userID'] = $userID;
        $_SESSION['fname'] = $fname;
        $_SESSION['role'] = $role;
        if($password === 'password'){
          $success['message'] = 'Login successful, please wait...';
          $success['redirect'] = '/changepassword';
        }else{
          $success['message'] = 'Login successful, please wait...';
          $success['redirect'] = '/dashboard';
        }        
      }else{
        $errors['invalidpass'] = 'Invalid Password!';
      }
    }else{
      $errors['emailPhone'] = 'Email or Phone does not exist!';
    }
  }

    if(count($errors) > 0){
      echo json_encode([
        'status' => false,
        'errors' => $errors,
      ]);
    }else{
      echo json_encode([
        'status' => true,
        'success' => $success,
      ]);
    }
}
