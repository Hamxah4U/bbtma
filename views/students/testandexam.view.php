<?php
   require './model/Database.php';
  require 'views/partials/security.php';
	require 'views/partials/header.php';

  require './views/partials/header.php';

?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div id="wrapper">
    <?php require 'views/partials/sidebar.php' ?>

  <div id="content-wrapper" class="d-flex flex-column">

		<div id="content">
				<?php
						require 'views/partials/nav.php';
				?>	        
        <div class="container-fluid">
          <div class="dataTable_wrapper" style="overflow: auto">
            <form id="addexam">
              <div class="form-row">
                <div class="col-md-12 mb-3">
                  <label for="validationCustom01">Class and Subject</label>
                  <select name="classandsubject" id="classDropdown" class="form-control">
                    <option value="--choose--">--Choose--</option>
                    <?php
                    $stmt = $db->conn->prepare("SELECT * FROM `usersubject_tbl` 
                      JOIN `class_tbl` ON `class_tbl`.`class_ID` = `usersubject_tbl`.`class` 
                      JOIN `subject_tbl` ON `subject_tbl`.`sub_ID` = `usersubject_tbl`.`subject` 
                      WHERE userID = :userID");
                    $stmt->execute(['userID' => $_SESSION['userID']]);
                    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($subjects as $subject): ?>
                      <option value="<?= $subject['class'] . '|' . $subject['sub_ID'] ?>">
                        <?= $subject['Class_Name'] . ' -- ' . $subject['Subject_name'] ?>
                      </option>
                    <?php endforeach ?>
                  </select>
                  <small class="text-danger" id="class"></small>
                </div>
              </div>
              <div id="studentTableContainer">
                <table class="table">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Full Name</th>
                      <!-- <th>Reg No</th> -->
                      <th>First Test</th>
                      <th>Second Test</th>
                      <th>Third Test</th>
                      <th>Exam</th>
                      <th>Total</th>
                    </tr>
                  </thead>
                  <tbody id="studentTableBody"></tbody>
                </table>
                <div>
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </div>
            </form>
          </div>
        </div>
				<!-- <div class="container-fluid">
          <div class="dataTable_wrapper" style="overflow: auto">
            <form id="addexam">
            <div class="form-row">
              <div class="col-md-12 mb-3">
                  <label for="validationCustom01">Class and Subject</label>
                  <select name="class" id="classDropdown" class="form-control">
                    <option value="--choose--"></option>
                    <?php
                      // $stmt = $db->conn->prepare("SELECT * FROM `usersubject_tbl` JOIN `class_tbl` ON `class_tbl`.`class_ID` = `usersubject_tbl`.`class` JOIN `subject_tbl` ON `subject_tbl`.`sub_ID` = `usersubject_tbl`.`subject` WHERE userID = '".$_SESSION['userID']."' ");          
                      // $stmt->execute();
                      // $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
                      // $i = 1;
                      // foreach($subjects as $subject):?>
                      <option value="<?php //$subject['class'] ?>"><?php // $subject['Class_Name'].' -- '.$subject['Subject_name'] ?></option>
                    <?php //endforeach ?>
                  </select>
                  <small class="text-danger" id="class"></small>               
              </div>                            
            </div>           
          </div>         
            <div id="studentTableContainer">
              <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Reg No</th>
                    <th>First Test</th>
                    <th>Second Test</th>
                    <th>Resit Test</th>
                    <th>Exam</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody id="studentTableBody">
                  
                </tbody>
              </table>
              <div> <button type="submit" class="btn btn-primary">Submit</button></div>
            </div>            
          </form>
				</div> -->
		</div>
<?php
    require 'views/partials/footer.php';
?>

<script>
  $(document).ready(function () {
    $('#classDropdown').change(function () {
      const selectedClass = $(this).val();

      if (selectedClass && selectedClass !== '--choose--') {
        $.ajax({
          url: 'model/ajax.fetch_students.php',
          method: 'POST',
          data: { class: selectedClass },
          success: function (response) {
            $('#studentTableBody').html(response);
          },
          error: function () {
            alert('An error occurred while fetching students.');
          },
        });
      } else {
        $('#studentTableBody').html('');
      }
    });

    $('#addexam').on('submit', function (e) {
      e.preventDefault();
        $('.text-danger').text('');
      $.ajax({
        url: 'model/addexam.php',
        data: $(this).serialize(),
        dataType: 'JSON',
        type: 'POST',
        success: function (response) {
          if(!response.status){
            $('#class').text(response.errors.classandsubject || '');
          }else{
            const Toast = Swal.mixin({
              toast: true,
              position: "top-end",
              showConfirmButton: false,
              timer: 2000,
              timerProgressBar: true,
              didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
              }
            });
            Toast.fire({
              icon: "success",
              title: response.success.message, //"Signed in successfully"
            });
            //alert(response.success.message);
            $('#addexam')[0].reset();
            $('#studentTableBody').html('');
          }
        },
        error: function () {
          alert('An error occurred while submitting the exam scores.');
        },
      });
    });
  });
</script>

<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
                responsive: true
        });
    });
</script>



