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

   if(empty($errors)){
    try{
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
            $mail->Host = 'bbtma.sfge.org.ng';
            $mail->SMTPAuth = true;
            $mail->Username = 'info@bbtma.sfge.org.ng';
            $mail->Password = '*H@mxah4u#';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
            $mail->Port = 465;

            // Email Settings
            $mail->setFrom('info@bbtma.sfge.org.ng', 'Baraya Baban Takko Memorial Academy, Bauchi.');
            $mail->addAddress($email, $fname); 
            $mail->isHTML(true);
            $mail->Subject = 'Registration Successful';
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                    <h1 style='color: #007bff;'>Welcome to Baraya Baban Takko Memorial Academy!</h1>
                    <p>Dear Mr./Mrs. $fname,</p>
                    <p>Congratulations on successfully completing your registration at Baraya Baban Takko Memorial Academy (BBTMA)! We are thrilled to have you as a part of our community. Your account has been created, and you can now access our platform to explore all the resources and opportunities available to you.</p>
                    <h3 style='color: #007bff;'>Your Account Details:</h3>
                    <ul style='list-style: none; padding: 0;'>
                    <li><strong>Username:</strong> $email or $phone</li>
                    <li><strong>Temporary Password:</strong> $pass</li>
                    </ul>
                    <p><strong>Important:</strong> Please ensure you update your password immediately after logging in to keep your account secure.</p>
                    <h3 style='color: #007bff;'>Next Steps:</h3>
                    <ol>
                    <li>Click the login button below to access your account.</li>
                    <li>Use your username and temporary password to log in.</li>
                    <li>Navigate to the account settings section to change your password.</li>
                    <li>Explore the platform and take advantage of all our offerings!</li>
                    </ol>
                    <p>
                    <a href='https://bbtma.sfge.org.ng/' style='text-decoration: none;'>
                        <button style='background-color: #007bff; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;'>Login</button>
                    </a>
                    </p>
                    <h3 style='color: #007bff;'>Need Assistance?</h3>
                    <p>If you encounter any issues or have questions, our support team is here to help. Feel free to reach out to us at <a href='mailto:support@bbtma.sfge.org.ng' style='color: #007bff; text-decoration: none;'>support@bbtma.sfge.org.ng</a>.</p>
                    <p>We are excited to have you on board and look forward to supporting you in achieving your goals at BBTMA!</p>
                    <p style='margin-top: 20px;'>Warm regards,</p>
                    <p style='font-weight: bold;'>BBTMA Admin Team</p>
                </div>";
            //sendSMS($phone, $fname, $pass);
            //$mail->send();
            $success['success'] = 'User successfully added, and email sent.';
        } catch (Exception $e) {
            $errors['emailError'] = 'Email could not be sent. Error: ' . $mail->ErrorInfo;
        }
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


function sendSMS($phone, $fname, $tempPassword) {
    $smsApiKey = 'ns6YqD4km8L0wp28sd5QSDulnfMuEpN9';
    $smsSender = 'BBTMA';
    $smsMessage = "Hello $fname, your registration at BBTMA was successful. Your temporary password is $tempPassword. Please log in and update it immediately.";
    $url = 'https://api.smsmode.com/http/1.6/sendSMS.do';
    $params = [
        'accessToken' => $smsApiKey,
        'message' => $smsMessage,
        'recipient' => $phone,
        'sender' => $smsSender,
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    
    if (!$response) {
        $errorMsg = 'CURL Error: ' . curl_error($ch);
        error_log($errorMsg);
        return ['status' => 'error', 'error' => 'Connection error: ' . $errorMsg];
    }
    
    curl_close($ch);
    $responseArray = json_decode($response, true);
    error_log('SMS API Response: ' . $response);
    
    if (isset($responseArray['status']) && $responseArray['status'] == 'success') {
        return ['status' => 'success'];
    } else {
        $errorMsg = $responseArray['message'] ?? 'SMS sending failed.';
        error_log('SMS sending failed: ' . $errorMsg);
        return ['status' => 'error', 'error' => $errorMsg];
    }
}
