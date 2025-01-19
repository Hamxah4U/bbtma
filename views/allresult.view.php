<?php
  require 'views/partials/security.php';
	require 'views/partials/header.php';
  function grade($Total){
    if($Total >= 70) {
        return 'A';
    } elseif($Total >= 60) {
        return 'B';
    } elseif($Total >= 50) {
        return 'C';
    } elseif($Total >= 45) {
        return 'D';
    } elseif($Total >= 40) {
        return 'E';
    } else {
        return 'F';
    }
  }

  function remark($Total){
    if($Total >= 70) {
        return 'Excellent';
    } elseif($Total >= 60) {
        return 'V.Good';
    } elseif($Total >= 50) {
        return 'Good';
    } elseif($Total >= 45) {
        return 'Pass';
    } elseif($Total >= 40) {
        return 'Poor';
    } else {
        return 'Fail';
    }
  }

  function position($studentposition){
    if($studentposition == 1){
      return "<sup>".'st'."</sup>";
    }elseif($studentposition == 2){
      return "<sup>".'nd'."</sup>";
    }elseif($studentposition == 3){
      return "<sup>".'rd'."</sup>";
    }else{
      return "<sup>".'th'."</sup>";
    }
  }

require 'model/Database.php';

?>

<div id="wrapper">
  <?php require 'views/partials/sidebar.php' ?>
  <div id="content-wrapper" class="d-flex flex-column">
    <!-- Main Content -->
    <div id="content">
      <!-- Topbar -->
      <?php	require 'views/partials/nav.php';	?>
      <div class="container-fluid">
        <input id='printButton' class='btn btn-primary' type='button' value='Print Result' onclick='PrintDoc2()' />
        <?php
          if(isset($_SESSION['report_result'])) {
            $results = $_SESSION['report_result']; 
          }else{
            echo "No report data available.";
            exit;
          }
        ?>
        <div id="printResult">
          <?php
            if (!isset($_SESSION['report_result'])) {
              die('No results found. Please try again.');
            }
            
            $students = $_SESSION['report_result'];
            
            foreach ($students as $student) {
                $studentID = $student['stu_ID'];
                $studentName = ucwords(strtolower($student['FirstName'] . " " . $student['OtherName'] . " " . $student['Surname']));
                $regNo = $student['Reg_no'];
                $className = $student['Class_Name'];
                $session = $student['Session'];
                $term = $student['Term'];
                $dob = date('d-m-Y', strtotime($student['DOB']));
                $passport = $student['Passphort'];
            
                echo "
                <div id='printResult'>
                    <center>
                        <img src='../../img/bbtmaReport.png' style='width: 100%;'>
                        <table style='width:70%; margin: auto;' id='mytablehead'>
                            <tbody>
                                <tr>
                                    <td><strong>$studentName</strong></td>
                                    <td><strong>$regNo</strong></td>
                                    <td><strong>$className</strong></td>
                                    <td><strong>$session</strong></td>
                                    <td><strong>$term</strong></td>
                                    <td>
                                        <center>
                                            <img src='../../model/students/uploads/$passport' alt='Photo' class='img-thumbnail' style='height: 80px; width: 100px;'>
                                            <br/> <i style='font-size: 11pt;'>$dob</i>
                                        </center>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </center>
                ";
            
                $stmt = $db->conn->prepare('SELECT s.*, sub.Subject_name 
                    FROM score_tbl s
                    JOIN subject_tbl sub ON s.subject = sub.sub_ID
                    WHERE s.stdID = :stdID AND s.session = :session AND s.term = :term
                ');
                $stmt->execute([
                    ':stdID' => $studentID,
                    ':session' => $student['Session'],
                    ':term' => $student['Term'],
                ]);
                $scores = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
                echo "<table class='table table-striped' style='width:80%;' id='tableContent'>
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Subject</th>
                        <th>1<sup>st</sup> Test</th>
                        <th>2<sup>nd</sup> Test</th>
                        <th>3<sup>rd</sup> Test</th>
                        <th>Exam</th>
                        <th>Total</th>
                        <th>Grade</th>
                        <th>Remark</th>
                    </tr>
                    </thead>
                    <tbody>";
            
                $totalscore = 0;
                $totalsubject = 0;
            
                foreach ($scores as $index => $score) {
                    $totalscore += $score['tscore'];
                    $totalsubject++;
                    echo "<tr>
                        <td>" . ($index + 1) . "</td>
                        <td>" . htmlspecialchars($score['Subject_name']) . "</td>
                        <td>" . htmlspecialchars($score['first']) . "</td>
                        <td>" . htmlspecialchars($score['second']) . "</td>
                        <td>" . htmlspecialchars($score['third']) . "</td>
                        <td>" . htmlspecialchars($score['exam']) . "</td>
                        <td>" . htmlspecialchars($score['tscore']) . "</td>
                        //<td>" . htmlspecialchars($score['grade']) . "</td>
                        //<td>" . htmlspecialchars($score['remark']) . "</td>
                    </tr>";                    
                }

                foreach ($results as $index => $row) {
                  $sql = $db->conn->prepare('SELECT Subject_name FROM subject_tbl WHERE sub_ID = :subject_id');
                  $sql->execute([':subject_id' => $row['subject']]);
                  $row2 = $sql->fetch(PDO::FETCH_ASSOC);
                  $subjectName = $row2 ? htmlspecialchars($row2['Subject_name']) : "Subject not found";

                  $totalscore += $row['tscore'];
                  $totalsubject = $index + 1;              
                    
                  $studentposition = isset($studentRanks[$row['stdID']]) ? $studentRanks[$row['stdID']] : 'N/A';

                  echo "<tr>";
                  echo "<td>" . ($index + 1) . "</td>";
                  echo "<td>" . htmlspecialchars($subjectName) . "</td>";
                  echo "<td>" . htmlspecialchars($row['first']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['second']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['third']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['exam']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['tscore']) . "</td>";
                  echo "<td>" . htmlspecialchars(grade($row['tscore'])) . "</td>"; 
                  echo "<td>" . htmlspecialchars(remark($row['tscore'])) . "</td>";  
                  echo "</tr>";              
                }  
            
                echo "</tbody></table></div>";
            }
          ?>
        </div>
      </div>
    </div>
    <?php  require 'views/partials/footer.php'; ?>

