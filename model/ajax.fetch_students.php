
<?php
require 'Database.php';

//is working perfectly
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['class'])) {
  [$classID, $subjectID] = explode('|', $_POST['class']);
  
  $stmt = $db->conn->prepare("SELECT * FROM student_tbl WHERE Class = :classID");
  $stmt->bindParam(':classID', $classID);
  $stmt->execute();
  $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if($students) {
    $i = 1;
    foreach ($students as $student) {
      $scoreStmt = $db->conn->prepare("SELECT * FROM score_tbl WHERE `stdID` = :stuID AND `stdclass` = :classID AND `subject` = :subjectID AND `session` = :session AND `term` = :term");
      $scoreStmt->bindParam(':stuID', $student['stu_ID']);
      $scoreStmt->bindParam(':classID', $classID);
      $scoreStmt->bindParam(':subjectID', $subjectID);
      $scoreStmt->bindParam(':session', $student['Session']);
      $scoreStmt->bindParam(':term', $student['Term']);
      $scoreStmt->execute();
      $score = $scoreStmt->fetch(PDO::FETCH_ASSOC);
      
      $ftest = $score['first'] ?? '';
      $stest = $score['second'] ?? '';
      $rtest = $score['third'] ?? '';
      $exam = $score['exam'] ?? '';
      $tscore = $score['tscore'] ?? '';
      $session = $score['session'] ?? '';
      $term = $score['term'] ?? '';

      echo
        '<tr>
          <td>' . $i++ . '</td>
          <td>' . htmlspecialchars($student['FirstName'] . ' ' . $student['OtherName'] . ' ' . $student['Surname']) . '</td>
          <input type="hidden" value="' . htmlspecialchars($student['Reg_no']) . '" name="regno[]" readonly>
          <td>
            <input type="hidden" name="stuid[]" value="' . htmlspecialchars($student['stu_ID']) . '">
            <input type="hidden" name="session" value="' . htmlspecialchars($student['Session']) . '">
            <input type="hidden" name="term" value="' . htmlspecialchars($student['Term']) . '">
            <input type="hidden" name="class" value="' . htmlspecialchars($classID) . '">
            <input type="hidden" name="subject" value="' . htmlspecialchars($subjectID) . '">
            <input type="number" name="ftest[]" style="width: 50px;" value="' . htmlspecialchars($ftest) . '"></td>
          <td><input type="number" name="stest[]" style="width: 50px;" value="' . htmlspecialchars($stest) . '"></td>
          <td><input type="number" name="rtest[]" style="width: 50px;" value="' . htmlspecialchars($rtest) . '"></td>
          <td><input type="number" name="exam[]" style="width: 50px;" value="' . htmlspecialchars($exam) . '"></td>
          <td><input type="number" name="total" style="width: 50px;" value="' . htmlspecialchars($tscore) . '" disabled></td>
        </tr>';
    }
  }else{
    echo '<tr><td colspan="7" class="text-center">No students found.</td></tr>';
  }
}

?>


