<?php
   require './model/Database.php';
  require 'views/partials/security.php';
	require 'views/partials/header.php';

  require './views/partials/header.php';

?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Page Wrapper -->
<div id="wrapper">
    <!-- Sidebar -->
    <?php require 'views/partials/sidebar.php' ?>

    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
  <div id="content-wrapper" class="d-flex flex-column">

		<!-- Main Content -->
		<div id="content">

				<!-- Topbar -->
				<?php
						require 'views/partials/nav.php';
				?>
				<!-- End of Topbar -->

				<!-- Begin Page Content -->
				<div class="container-fluid">
          <div class="dataTable_wrapper" style="overflow: auto">
            <form action="" id="addexam">
            <div class="form-row">
              <div class="col-md-12 mb-3">
                  <label for="validationCustom01">Class and Subject</label>
                  <select name="class" id="classDropdown" class="form-control">
                    <option value="--choose--"></option>
                    <?php
                      $stmt = $db->conn->prepare("SELECT * FROM `usersubject_tbl` JOIN `class_tbl` ON `class_tbl`.`class_ID` = `usersubject_tbl`.`class` JOIN `subject_tbl` ON `subject_tbl`.`sub_ID` = `usersubject_tbl`.`subject` WHERE userID = '".$_SESSION['userID']."' ");          
                      $stmt->execute();
                      $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
                      $i = 1;
                      foreach($subjects as $subject):?>
                      <option value="<?= $subject['class'] ?>"><?= $subject['Class_Name'].' -- '.$subject['Subject_name'] ?></option>
                    <?php endforeach ?>
                  </select>
                  <small class="text-danger" id="class"></small>               
              </div>                            
            </div>
            </form>
          </div>
          <form id="addexam"> 
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
                  <!-- Dynamic rows will be appended here -->
                </tbody>
              </table>
              <div> <button type="submit" class="btn btn-primary">Submit</button></div>
            </div>
            
          </form>

				</div>
				<!-- /.container-fluid -->

		</div>
		<!-- End of Main Content -->

<?php
    require 'views/partials/footer.php';
?>

<script>
  $('#addexam').on('submit', function(e) {
    console.log('Form submit triggered');
    e.preventDefault();
    $.ajax({
        url: 'model/addexam.php',
        data: $(this).serialize(),
        dataType: 'JSON',
        type: 'POST',
        success: function(response) {
            if (!response.errors) {
                alert('empty');
            } else {
                alert('success');
            }
        },
        error: function() {
            alert('error');
        }
    });
});

</script>

<script>
  $(document).ready(function () {
  $('#classDropdown').change(function () {
    const selectedClass = $(this).val();

    if (selectedClass) {
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
});

</script>

<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
                responsive: true
        });
    });
</script>


