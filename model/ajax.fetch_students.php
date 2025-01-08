<?php
require 'Database.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['class'])) {
  $classID = $_POST['class'];
  
  $stmt = $db->conn->prepare("SELECT * FROM `student_tbl` WHERE `Class` = :classID");
  $stmt->bindParam(':classID', $classID);
  $stmt->execute();
  $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if ($students) {
    $i = 1;
    foreach ($students as $student) {
      echo '<tr>
              <td>' . $i++ . '</td>
              <td>' . htmlspecialchars($student['FirstName'] . ' ' . $student['OtherName'] . ' ' . $student['Surname']) . '</td>
              <td><input type="text" value="' . htmlspecialchars($student['Reg_no']) . '" name="regno[]"></td>
              <td><input type="number" name="ftest[]" style="width: 40px;"></td>
              <td><input type="number" name="stest[]" style="width: 40px;"></td>
              <td><input type="number" name="rtest[]" style="width: 40px;"></td>
              <td><input type="number" name="exam[]" style="width: 40px;"></td>
              <td><input type="number" name="total[]" style="width: 40px;"></td>
            </tr>';
    }
  } else {
    echo '<tr><td colspan="8" class="text-center">No students found.</td></tr>';
  }
}
?>

