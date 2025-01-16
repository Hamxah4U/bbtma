<?php
require 'Database.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

    if ($phoneExist > 0) {
        $errors['phoneExist'] = 'Phone number already exists!';
    }

    if ($emailExist > 0) {
        $errors['emailExist'] = 'Email address already exists!';
    }

    if (empty($fname)) {
        $errors['fname'] = 'Fullname is required!';
    }

    if (empty($email)) {
        $errors['email'] = 'Email is required!';
    }

    if (empty($phone)) {
        $errors['phone'] = 'Phone is required!';
    }

    if ($role == '--choose--') {
        $errors['role'] = 'Please choose a role!';
    }

    if (empty($errors)) {
        try {
            $db = new Database();
            $stmt = $db->conn->prepare('INSERT INTO `users_tbl` (`Fullname`, `Email`, `Phone`, `UserPassword`, `Role`) VALUES (:fname, :email, :phone, :pass, :userrole)');
            $stmt->bindParam(':fname', $fname, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
            $stmt->bindParam(':pass', $password, PDO::PARAM_STR);
            $stmt->bindParam(':userrole', $role, PDO::PARAM_STR);
            $result = $stmt->execute();
            if ($result) {
                // Send Email
                $mail = new PHPMailer(true);
                try {
                    // SMTP Server Settings
                    $mail->isSMTP();
                    $mail->Host = ''; //example.com
                    $mail->SMTPAuth = true;
                    $mail->Username = ''; //info@example.eg.org.ng
                    $mail->Password = ''; //**********/
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
                    $mail->Port = 465;

                    // Email Settings
                    $mail->setFrom('info@bbtma.sfge.org.ng', 'BBTMA Admin');
                    $mail->addAddress($email, $fname); 
                    $mail->isHTML(true);
                    $mail->Subject = 'Registration Successful';
                    $mail->Body = "
                      <h2>Welcome to Baraya Baban Takko Memorial Academy Mr./Mrs, $fname!</h2>
                      <p>Your registration was successful. Your username is <strong>$email or $phone</strong>. and your temporary password is <strong>$pass</strong>. Please update your password after login.</p>
                      <p>
                        <a href='https://bbtma.sfge.org.ng/' style='text-decoration: none;'>
                          <button style='background-color: #007bff; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;'>Login</button>
                        </a>
                      </p>
                    ";

                    $mail->send();
                    $success['success'] = 'User successfully added, and email sent.';
                } catch (Exception $e) {
                    $errors['emailError'] = 'Email could not be sent. Error: ' . $mail->ErrorInfo;
                }
            }
        } catch (PDOException $e) {
            die('Error: ' . $e->getMessage());
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