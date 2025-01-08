<?php
    require '../Database.php';
    //var_dump($db);

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $errors = [];
      $success = [];

      $fname = $_POST['fname'];
      $sname = $_POST['sname'];
      $mname = $_POST['mname'];
      $gender = $_POST['gender'];
      $phone = $_POST['phone'];
      $email = $_POST['email'];
      $stdsession = $_POST['stdsession'];
      $class = $_POST['class'];
      $term = $_POST['term'];
      $mname = $_POST['mname'];
      $dob = $_POST['dob'];
      $address = $_POST['address'];
      $featuredImage = $_FILES['image'];
      $reg_no = $_POST['reg_no'];

      if($gender == '--choose--'){
        $errors['gender'] = 'Gender is required!';
      }

      if($stdsession == '--choose--'){
        $errors['stdsession'] = 'Session is required!';
      }

      if($term == '--choose--'){
        $errors['term'] = 'Term is required!';
      }

      // if(empty(trim($studentnumber))){
      //   $errors['studentnumber'] = 'Registration number is required!';
      // }

      if($class == '--choose--'){
        $errors['class'] = 'Student class cannot be empt!';
      }

      if(empty(trim($phone))){
        $errors['phone'] = 'Phone is required!';
      }

      if(empty(trim($sname))){
        $errors['sname'] = 'Surname is required!';
      }

      if(empty(trim($fname))){
        $errors['fname'] = 'Firstname is required!';
      }

      if (!empty($featuredImage['name'])) {
        $targetDir = __DIR__ . '/uploads/';
        
        $fileType = strtolower(pathinfo($featuredImage['name'], PATHINFO_EXTENSION));
        $fileName = uniqid('img_', true) . '.' . $fileType;
        $targetFilePath = $targetDir . $fileName;
    
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileType, $allowedTypes)) {
            $errors['image'] = 'Only JPG, JPEG, PNG, and GIF files are allowed!'.$targetDir;
        } elseif (!move_uploaded_file($featuredImage['tmp_name'], $targetFilePath)) {
            $errors['image'] = 'Failed to upload the image!';
        }
        } else {
        $errors['image'] = 'Image is required!';
      }

      if(empty($errors)){
        session_start();
        $user = $_SESSION['email'];
        $stmt = $db->conn->prepare('INSERT INTO student_tbl(FirstName, Surname, Gender, Reg_no, Session, Term, Class,OtherName,dob,`Address`,Phone,Passphort,AddedBy) VALUES (:FirstName, :Surname, :Gender, :Reg_no, :Session, :Term, :Class,:mname,:dob,:Address,:Phone,:Passphort,:AddedBy)');
        $stmt->bindParam(':FirstName', $fname, PDO::PARAM_STR);
        $stmt->bindParam(':Surname', $sname, PDO::PARAM_STR);
        $stmt->bindParam(':Gender', $gender);
        $stmt->bindParam(':Reg_no', $reg_no);
        $stmt->bindParam(':Term', $term);
        $stmt->bindParam(':Session', $stdsession);
        $stmt->bindParam(':Class', $class);
        $stmt->bindParam(':mname', $mname);
        $stmt->bindParam(':dob', $dob);
        $stmt->bindParam(':Address', $address);
        $stmt->bindParam(':Phone', $phone);
        $stmt->bindParam(':Passphort', $fileName);
        $stmt->bindParam(':AddedBy', $user);

        $result = $stmt->execute();
        if($result){
          $success['message'] = 'Form submitted successfully!';
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
          'success' => $success
        ]);
      }

    }
?>