<?php
// require '../../model/Database.php';
require './model/Database.php';
  require 'views/partials/security.php';
	require 'views/partials/header.php';
	//require 'views/partials/Users.class.php';

  if(isset($_GET['id'])){
    $id = $_GET['id'];
    $stmt = $db->conn->prepare("SELECT * FROM `users_tbl` WHERE user_ID = '$id'  ");
    $stmt->execute();
    $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);
  }

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
          <p>Assign Subject to: <strong><?= $userInfo['Fullname'] ?></strong></p>
          <form  method="post" id="assignsubject">
            <input type="hidden" name="userID" value="<?=  $userInfo['user_ID'] ?>">
            <div class="form-row">
              <div class="col-md-6 mb-3">
                  <label for="validationCustom01">Class</label>
                  <select name="class" id="classDropdown" class="form-control">
                    <option value="--choose--"></option>
                    <?php
                      $stmt = $db->conn->prepare('SELECT * FROM `class_tbl` WHERE `Status` = "Active" GROUP BY `Class_Name` ORDER BY `Class_Name` ');
                      $stmt->execute();
                      $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                      foreach($classes as $class):?>
                      <option value="<?= $class['class_ID'] ?>"><?= $class['Class_Name'] ?></option>
                    <?php endforeach ?>
                  </select>
                  <small class="text-danger" id="class"></small>               
              </div>
              <div class="col-md-6 mb-3">
                  <label for="validationCustom02">Subject</label>
                  <select name="subjectuser" id="subjectDropdown" class="form-control">
                    <option value="--choose--"></option>
                  </select>
                  <small class="text-danger" id="subjectuser"></small>
              </div>               
            </div>
            <button type="submit" class="btn btn-primary">Add</button>

          </form>
          <table class="table table-striped table-bordered table-hover" id="dataTables-example" style="width:100%">
            <thead>
              <tr>
                <th>#</th>
						    <th>Class</th>
						    <th>Subject</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            <?php
              $stmt = $db->conn->prepare("SELECT * FROM `usersubject_tbl` JOIN `class_tbl` ON `class_tbl`.`class_ID` = `usersubject_tbl`.`class` JOIN `subject_tbl` ON `subject_tbl`.`sub_ID` = `usersubject_tbl`.`subject` WHERE userID = '".$userInfo['user_ID']."' ");          
              $stmt->execute();
              $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
              $i = 1;
              foreach($users as $user):?>
                <tr>
                  <td><?= $i++ ?></td>
                  <td><?= $user['Class_Name'] ?></td>
                  <td><?= $user['Subject_name'] ?></td>
                  <td>
                    <a href="/assigsub?id=<?= $user['id']; ?>" title="Edit Student Information"><p class="icofont-trash" style="float: left;color:red"></p></a>
                  </td>
                </tr>
              <?php endforeach ?>
                </tbody>
            </table>
            </div> 
				</div>
				<!-- /.container-fluid -->

		</div>
		<!-- End of Main Content -->

<?php
    require 'views/partials/footer.php';
?>

<script>
  $(document).ready(function(){
    $('#assignsubject').on('submit', function(e){
      e.preventDefault();
      $('.text-danger').text('');
      $.ajax({
        url: 'model/assignsubject.php',
        dataType: 'JSON',
        data: $(this).serialize(),
        type: 'POST',
        success: function(response){
          if(! response.status){
            //alert('empty');
            $('#class').text(response.errors.class || '');
            $('#subjectuser').text(response.errors.subjectuser || '') ;
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
              title: "Subject assign successfully"
            });
          }
        },
        error: function(error){
          alert('error' + error)
        }
      })
    })
  })
</script>

<script>
  $(document).ready(function () {
    $('#classDropdown').on('change', function () {
        var classID = $(this).val();
        if (classID) {
            $.ajax({
                url: 'model/ajax.subject.php',
                type: 'POST',
                data: { class_ID: classID },
                dataType: 'json',
                success: function (response) {
                    $('#subjectDropdown').empty();
                    $('#subjectDropdown').append('<option value="--choose--">Select Subject</option>');

                    $.each(response, function (key, subject) {
                        $('#subjectDropdown').append('<option value="' + subject.sub_ID + '">' + subject.Subject_name + '</option>');
                    });
                },
                error: function () {
                    alert('Failed to fetch subjects. Please try again.');
                }
            });
        } else {
            $('#subjectDropdown').empty();
            $('#subjectDropdown').append('<option value="--choose--">Select Subject</option>');
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


