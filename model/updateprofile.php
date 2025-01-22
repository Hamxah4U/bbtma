<?php
  require 'Database.php';

  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $errors = [];
    $success = [];

    $fname = $_POST['fname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender= $_POST['gender'];
    $address= $_POST['address'];
    $id = $_POST['stdID'];

    if(empty(trim($fname))){
      $errors['errorFirstname'] = 'Fullname cannot be empty!';
    }

    if(empty(trim($email))){
      $errors['email'] = 'Email cannot be empty';
    }

    if(empty(trim($phone))){
      $errors['phone'] = 'Phone cannot be empty';
    }

    if(empty(trim($address))){
      $errors['address'] = 'Address cannot be empty';
    }

    if (empty($errors)) {
      $stmt = $db->conn->prepare("UPDATE users_tbl SET 
          Fullname = :fname, 
          Phone = :phone, 
          Address = :address,
          Email = :email
          WHERE user_ID = :staffID");
          
      $result = $stmt->execute([
          ':fname' => $fname,
          ':phone' => $phone,
          ':address' => $address,
          ':email' => $email,
          ':staffID' => $id
      ]);
  
      if ($result) {
          $success['message'] = 'Profile updated successfully!';
      } else {
          $errors[] = 'Error updating profile. Please try again.';
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
        'success' => $success
      ]);
    }

  }
?>