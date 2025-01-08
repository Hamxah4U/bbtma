<?php
require 'Database.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $success = [];
  $errors = [];
  $fname = trim($_POST['fname']);
  $email = trim($_POST['email']);
  $phone = trim($_POST['phone']);
  $role = trim($_POST['role']);
  
  $pass = 'password';
  $password = password_hash($pass, PASSWORD_BCRYPT);



  $stmtEmail = $db->checkExist('SELECT `Email` FROM `users_tbl` WHERE `Email` = :email', ['email' => $email]);
  $emailExist = $stmtEmail->rowCount();

  $stmtPhone = $db->checkExist('SELECT `Phone` FROM `users_tbl` WHERE `Phone` = :phone', ['phone' => $phone]);
  $phoneExist = $stmtPhone->rowCount();


  if($phoneExist > 0){
    $errors['phoneExist'] = 'Phone number already exists!';
  }

  if($emailExist > 0){
    $errors['emailExist'] = 'Email address already exists!';
  }

  

  if(empty($fname)) {
    $errors['fname'] = 'Fullname is required!';
  }

  if(empty($email)) {
    $errors['email'] = 'Email is required!';
  }

  if(empty($phone)) {
    $errors['phone'] = 'Phone is required!';
  }

  if($role == '--choose--') {
    $errors['role'] = 'Please choose a role!';
  }

  if(empty($errors)){
    try{
      $db = new Database();
      $stmt = $db->conn->prepare('INSERT INTO `users_tbl` (`Fullname`,`Email`,`Phone`,`UserPassword`,`Role`) VALUES (:fname, :email, :phone, :pass, :userrole) ');
      $stmt->bindParam(':fname', $fname, PDO::PARAM_STR);
      $stmt->bindParam(':email', $email, PDO::PARAM_STR);
      $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
      $stmt->bindParam(':pass', $password, PDO::PARAM_STR);
      $stmt->bindParam(':userrole', $role, PDO::PARAM_STR);
      $result = $stmt->execute();
      if($result){
        $success['success'] = 'User successfully added';
      }
    }catch(PDOException $e){
      die('Error:'. $e->getMessage());
    }
  }

  if (count($errors) > 0) {
      echo json_encode([
          'status' => false,
          'errors' => $errors,
      ]);
  } else {
      echo json_encode([
        'status' => true,
        'success' => $success,
      ]);
  }
}

?>