<?php
  require './model/Database.php';
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
  
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
@media print {
  img {
    display: block !important;
  }
}

@page {
  size: portrait;
}

img {
  max-width: 100%;
  height: auto;
}
</style>
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
          <center>
            <img src="../../img/bbtmaReport.png" style="width: 100%;">
            <table class="" style="width:70%; margin: auto;" id="mytablehead">
              <tbody>
                <?php                 
                  $student = $_SESSION['report_result'][0];
                  $sql = $db->conn->prepare('SELECT * FROM `student_tbl`
                  JOIN class_tbl ON `student_tbl`.`Class` = `class_tbl`.`class_ID`
                  JOIN `session_tbl` ON `session_tbl`.`s_ID` = `student_tbl`.`Session`
                  JOIN `term_tbl` ON `term_tbl`.`id` = `student_tbl`.`Term`
                  WHERE stu_ID = :stu_ID LIMIT 1');
                  $sql->execute([':stu_ID' => $student['stdID']]);
                  $studentDetails = $sql->fetch(PDO::FETCH_ASSOC);
                  
                  $original_date = $studentDetails['DOB'];
                  $date_components = explode('-', $original_date);
                  $new_date = $date_components[2] . '-' . $date_components[1] . '-' . $date_components[0];

                  echo "<tr>";
                  echo "<td><strong>" . ucwords(strtolower($studentDetails['FirstName'])) . " " . ucwords(strtolower($studentDetails['OtherName'])) ." ".ucwords(strtolower($studentDetails['Surname'])) ."</strong></td>";
                  echo "<td><strong>" . $studentDetails['Reg_no'] . "</strong></td>";
                  echo "<td><strong>" . htmlspecialchars($studentDetails['Class_Name']) . "</strong></td>";
                  echo "<td><strong>" . htmlspecialchars($studentDetails['Session']) . "</strong></td>";
                  echo "<td><strong>" . htmlspecialchars($studentDetails['term']) . "</strong></td>";
                  echo "<td><center>
                    <img src='../../model/students/uploads/" . $studentDetails['Passphort'] . "' alt='Photo' class='img-thumbnail' style='height: 80px; width: 100px;'>
                    <br/> <i style='font-size: 11pt;'>$new_date</i>
                  </center></td>";
                  echo "</tr>";
                  
                ?>
              </tbody>
            </table>

            <table class="table table-striped" style="width:80%;" id="tableContent">
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
              <tbody>
                <?php             
                
                foreach ($results as $index => $row) {
                  $sql = $db->conn->prepare('SELECT `Subject_name` FROM `subject_tbl` WHERE sub_ID = :subject_id');
                  $sql->execute([':subject_id' => $row['subject']]);
                  $row2 = $sql->fetch(PDO::FETCH_ASSOC);
                  $subjectName = $row2 ? htmlspecialchars($row2['Subject_name']) : "Subject not found";
                                                               
                }  
                ?>
              </tbody>

              <tbody>
                <?php             
                $totalscore = 0;
                $totalsubject = 0;
                $studentposition = 0;
    
                $stmt = $db->conn->prepare('SELECT SUM(tscore) AS totalscore, s.stdID, s.Reg_no, s.stdclass, s.session, s.term
                  FROM score_tbl s
                  WHERE s.session = :session AND s.term = :term AND s.stdclass = :stdclass
                  GROUP BY s.stdID, s.Reg_no, s.stdclass, s.session, s.term
                  ORDER BY totalscore DESC');
                
                $stmt->execute([
                  ':session' => $row['session'],  
                  ':term' => $row['term'],        
                  ':stdclass' => $row['stdclass'],
                ]);
    
                $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                $studentRanks = [];
                foreach ($students as $index => $student) {
                  $studentRanks[$student['stdID']] = $index + 1; 
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
                ?>
              </tbody>
            </table>
          </center>

          <table style="width:80%; margin: auto;">
            <?php
              $avg = $totalscore/$totalsubject;  
              $totalStudent = $db->conn->prepare('SELECT * FROM `positio` s 
              WHERE s.session = :session AND s.term = :term AND s.stdclass = :stdclass') ;
              $totalStudent->execute([
                ':session' => $row['session'],  
                ':term' => $row['term'],        
                ':stdclass' => $row['stdclass'],
              ]);
              $tstudent = $totalStudent->rowCount();
            ?>
            <tr>
              <td><strong style='font-size: 12pt;'>Position: <span style='font-size: 14pt;'><?= $studentposition.position($studentposition);?></span>
                  Out of <span style='font-size: 14pt;'><?= $tstudent;?></span> Pupils</strong></td>
              <td><strong style='font-size: 11pt;'>Total Score: <span
                    style='font-size: 14pt;'><?= $totalscore ?></span></strong></td>
              <td><strong style='font-size: 11pt;'>Average: <span
                    style='font-size: 14pt;'><?php echo number_format((float)$avg,2,'.',);//sprintf("%.2f",$average); ?></span></strong>
              </td>
              <td><strong style='font-size: 11pt;'>Promoted to: _____________</strong></td>
            </tr>
            <tr>
              <td><strong style='font-size: 11pt;'>Closing Date _________</strong></td>
              <td><strong style='font-size: 11pt;'>Resumption Date _________</strong></td>
              <td colspan="2"><strong style='font-size: 11pt;'>Class Teacher Comment:_____________</strong></td>
            </tr>
            <tr>
              <td><strong style='font-size: 11pt;'>Next Term School Fees:_________</strong></td>
              <td><strong style='font-size: 11pt;'>Pre-Bal:_________</strong></td>
              <td><strong style='font-size: 11pt;'>Net Bal___________</strong></td>
              <td>&nbsp;</td>
            </tr>
          </table>
        </div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/printThis/1.15.0/printThis.min.js"></script>

    <script>
    $(document).ready(function() {
      $("#printButton").click(function() {
        $("#printResult").printThis({
          importCSS: true, // import page CSS
          importStyle: true, // import style tags
          loadCSS: "", // load additional CSS
          pageTitle: "", // add title to print page
          removeInline: false, // remove inline styles
          printDelay: 333, // delay print until other styles load
          header: null, // prefix to html
          footer: null, // postfix to html
          base: false, // preserve the BASE tag or accept a string for the URL
          formValues: true, // preserve input/form values
          canvas: false, // copy canvas content
          doctypeString: '<!DOCTYPE html>', // doctype string for the print document
          removeScripts: false, // remove script tags from print content
          copyTagClasses: false // copy classes from the html & body tag
        });
      });
    });
    </script>

<?php  //require 'views/partials/footer.php'; ?>
