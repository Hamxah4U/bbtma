<?php
// require '../../model/Database.php';
require './model/Database.php';
  require 'views/partials/security.php';
	require 'views/partials/header.php';
	//require 'views/partials/Users.class.php';

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
          <table class="table table-striped table-bordered table-hover" id="dataTables-example" style="width:100%">
            <thead>
              <tr>
                <th>#</th>
						    <th>Fullname</th>
						    <th>Adm No.</th>
                <th>Gender</th>
						    <th>DOB</th>
						    <th>Class</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            <?php
              $stmt = $db->conn->prepare('SELECT * FROM `student_tbl` JOIN `gender_tbl` ON `gender_tbl`.`id` = `student_tbl`.`Gender` JOIN class_tbl ON `class_tbl`.`class_ID` = `student_tbl`.`Class`');          
              // $stmt = $db->conn->prepare('SELECT * FROM `student_tbl` ORDER BY Student_Class ASC');          
              //$sqlsub = mysqli_query($conn, "SELECT * FROM `class_tbl` WHERE `class_ID` = '".$row['Student_Class']."' ") or die(mysqli_error($conn));
              $stmt->execute();
              $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
              $i = 1;
              foreach($students as $std):?>
                <tr>
                  <td><?= $i++ ?></td>
                  <td><?= $std['FirstName'].' '.$std['Surname'].' '.$std['OtherName'] ?></td>
                  <td><?= $std['Reg_no'] ?></td>
                  <td><?= $std['Gender'] ?></td>
                  <td><?= $std['DOB'] ?></td>
                  <td><?= $std['Class_Name'] ?></td>
                  <td>
                    <a href="/editstudent?editstd=<?= $std['stu_ID']; ?>" title="Edit Student Information"><p class="icofont-edit" style="float: left;"></p></a>
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
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
                responsive: true
        });
    });
</script>


