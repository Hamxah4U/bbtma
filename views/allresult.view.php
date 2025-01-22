<?php
  require './model/Database.php';
  require 'views/partials/security.php';
	require 'views/partials/header.php';
  require 'vendor/autoload.php';
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Label\Font\NotoSans;


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

  @media print {
  .printResult {
    page-break-after: always;
  }
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
            $_SESSION['report_result'] = array_unique($_SESSION['report_result'], SORT_REGULAR);
            $results = $_SESSION['report_result'];            
          }else{
            echo "No report data available.";
            exit;
          }
          if (!isset($_SESSION['report_result'])) {
            die('No results found. Please try again.');
          }
            
          $students = $_SESSION['report_result']; 
          
          foreach($students as $index => $student):

            $stmtposition = $db->conn->prepare('SELECT SUM(tscore) AS totalscore, s.stdID, s.Reg_no, s.stdclass, s.session, s.term
              FROM score_tbl s
              WHERE s.session = :session AND s.term = :term AND s.stdclass = :stdclass/*  AND stdID = :stdID  */
              GROUP BY s.stdID, s.Reg_no, s.stdclass, s.session, s.term
              ORDER BY totalscore DESC');                
            $stmtposition->execute([
              //':stdID' => $student['stdID'],
              ':session' => $student['session'],
              ':term' => $student['term'],
              ':stdclass' => $student['stdclass']
            ]);
  
            $studentPositions = $stmtposition->fetchAll(PDO::FETCH_ASSOC);
              
            $studentRanks = [];
            foreach ($studentPositions as $index => $studentPosition) {
              $studentRanks[$studentPosition['stdID']] = $index + 1; 
            }

            $stmt = $db->conn->prepare('SELECT * FROM `student_tbl`WHERE `stu_ID` = :stu_ID ');
            $stmt->execute([':stu_ID' => $student['stdID']]);
            $studentInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $sessionsql = $db->conn->prepare('SELECT * FROM `session_tbl` WHERE s_ID = :id');
            $sessionsql->execute([':id' => $student['session'] ]);
            $session = $sessionsql->fetchAll(PDO::FETCH_ASSOC);

            $termsql = $db->conn->prepare('SELECT * FROM `term_tbl` WHERE id = :id');
            $termsql->execute([':id' => $student['term']]);
            $terms = $termsql->fetchAll(PDO::FETCH_ASSOC);

            $classSql = $db->conn->prepare('SELECT * FROM `class_tbl` WHERE class_ID = :class_ID');
            $classSql->execute([':class_ID' => $student['stdclass']]);
            $classes = $classSql->fetchAll(PDO::FETCH_ASSOC);

            $subjectsql = $db->conn->prepare('SELECT s.*, sub.Subject_name 
              FROM score_tbl s
              JOIN subject_tbl sub ON s.subject = sub.sub_ID
              WHERE s.stdID = :stdID AND s.session = :session AND s.term = :term');
            $subjectsql->execute([
              ':stdID' => $student['stdID'],
              ':session' => $student['session'],
              ':term' => $student['term'],
            ]);
            $subjects = $subjectsql->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <!-- <div id="printResult"> -->
        <div id="printResult<?= $index ?>" class="printResult">
          <center>
            <img src='../../img/bbtmaReport.png' style='width: 100%;'>

            <table style='width:70%; margin: auto;' id='mytablehead'>
              <tbody>
                <tr>
                  <td><strong>
                    <?php
                      foreach($studentInfo as $stdinfo){
                        echo ucwords(strtolower($stdinfo['FirstName'] . " " . $stdinfo['OtherName'] . " " . $stdinfo['Surname']));
                    } ?>
                  </strong></td>
                  <td><strong><?= $student['Reg_no'] ?></strong></td>
                  <td><strong><?php 
                    foreach($classes as $class){
                      echo $class['Class_Name'];
                    }
                      ?></strong></td>
                  <td><strong>
                    <?php  
                      foreach($session as $sess){
                        echo $sess['Session'];
                      }
                    ?></strong></td>
                  <td><strong>
                    <?php
                      foreach($terms as $term){
                        echo $term['term'];
                      }
                    $student['term'] ?></strong></td>
                  <td>
                    <center>
                      <img src='../../model/students/uploads/<?= $stdinfo['Passphort'] ?>' alt='passport' class='img-thumbnail' style='height: 80px; width: 100px;'>
                      <br/> <i style='font-size: 11pt;'><?= date('d-m-Y', strtotime($stdinfo['DOB'])) ?></i>
                    </center>
                  </td>
                </tr>
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
                    $totalscore = 0;
                    $totalsubject = 0;
                    $studentposition = 0;
                  foreach ($subjects  as $index => $row):
                    $totalscore += $row['tscore'];
                    $totalsubject = $index + 1;
                    $studentposition = isset($studentRanks[$row['stdID']]) ? $studentRanks[$row['stdID']] : 'N/A';
                  ?>
                  <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= $row['Subject_name'] ?></td>
                    <td><?= $row['first'] ?></td>
                    <td><?= $row['second'] ?></td>
                    <td><?= $row['third'] ?></td>
                    <td><?= $row['exam'] ?></td>
                    <td><?= $row['tscore'] ?></td>
                    <td><?= grade($row['tscore'] )?></td>
                    <td><?= remark($row['tscore']) ?></td>
                  </tr>
                <?php endforeach ?>
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
        <?php endforeach ?>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/printThis/1.15.0/printThis.min.js"></script>

    <script>
      $(document).ready(function() {
  $("#printButton").click(function() {
    $(".printResult").printThis({
      importCSS: true,
      importStyle: true,
      loadCSS: "",
      pageTitle: "",
      removeInline: false,
      printDelay: 333,
      header: null,
      footer: null,
      base: false,
      formValues: true,
      canvas: false,
      doctypeString: '<!DOCTYPE html>',
      removeScripts: false,
      copyTagClasses: false
    });
  });
});

      /* $(document).ready(function() {
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
      }); */
    </script>

<?php  //require 'views/partials/footer.php'; ?>
