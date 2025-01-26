<?php
    require '../Database.php';
  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $stdID = $_POST['stdID'];
    $sname = $_POST['sname'];
    $mname = $_POST['mname'];
    $fname = $_POST['fname'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $term = $_POST['term'];
    $stdsession = $_POST['stdsession'];
    $class = $_POST['class'];
    $address = $_POST['address'];
    $featuredImage = $_FILES['image'];
    $dob = $_POST['dob'];
    $errors = [];
    $success = [];

    if(empty(trim($sname))){
      $errors['sname'] = 'Required!';
    }

    if(empty($fname)){
      $errors['fname'] = 'Required!';
    }

    if (!empty($featuredImage['name'])) {
      $targetDir = __DIR__ . '/uploads/';
      $fileType = strtolower(pathinfo($featuredImage['name'], PATHINFO_EXTENSION));
      $fileName = uniqid('img_', true) . '.' . $fileType;
      $targetFilePath = $targetDir . $fileName;
  
      $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
      if (!in_array($fileType, $allowedTypes)) {
          $errors['image'] = 'Only JPG, JPEG, PNG, and GIF files are allowed!';
      } else {
          // Fetch the existing image from the database
          $stmt = $db->conn->prepare("SELECT Passphort FROM student_tbl WHERE stu_ID = ?");
          $stmt->execute([$stdID]);
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
  
          // Check if there is an existing image
          if (!empty($row['Passphort'])) {
              $existingFilePath = $targetDir . $row['Passphort'];
              if (file_exists($existingFilePath)) {
                  // Delete the existing file
                  unlink($existingFilePath);
              }
          }
  
          // Upload the new file
          if (!move_uploaded_file($featuredImage['tmp_name'], $targetFilePath)) {
              $errors['image'] = 'Failed to upload the image!';
          }
      }
  } else {
      // Retain the existing image if no new image is provided
      $stmt = $db->conn->prepare("SELECT Passphort FROM student_tbl WHERE stu_ID = ?");
      $stmt->execute([$stdID]);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $fileName = $row['Passphort'];
  }
  

    /* if (!empty($featuredImage['name'])) {
      $targetDir = __DIR__ . '/uploads/';
      $fileType = strtolower(pathinfo($featuredImage['name'], PATHINFO_EXTENSION));
      $fileName = uniqid('img_', true) . '.' . $fileType;
      $targetFilePath = $targetDir . $fileName;

      $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
      if (!in_array($fileType, $allowedTypes)) {
          $errors['image'] = 'Only JPG, JPEG, PNG, and GIF files are allowed!';
      } elseif (!move_uploaded_file($featuredImage['tmp_name'], $targetFilePath)) {
          $errors['image'] = 'Failed to upload the image!';
      }
    } else {
      $stmt = $db->conn->prepare("SELECT Passphort FROM student_tbl WHERE stu_ID = ?");
      $stmt->execute([$stdID]);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $fileName = $row['Passphort'];
    } */

    if(empty($errors)){
      $stmt = $db->conn->prepare("UPDATE `student_tbl` SET Passphort='$fileName', Email='$email',Phone='$phone',`Address`='$address',DOB='$dob', `Class`='$class',Term='$term',`Session`='$stdsession', Gender='$gender',OtherName='$mname', FirstName = '$fname', Surname='$sname' WHERE stu_ID='$stdID' ");
      $result = $stmt->execute();
      if($result){
        $success['message'] = 'Record updated successfully!';
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