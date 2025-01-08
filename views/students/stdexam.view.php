<?php
  require './model/Database.php';
  require 'views/partials/security.php';
	require 'views/partials/header.php';

  require './views/partials/header.php';

  if(isset($_GET['id'])){
    $id = $_GET['id'];
    $stmtstd = $db->conn->prepare("SELECT * FROM `student_tbl` WHERE `Class` = '$id'");
    $stmtstd->execute();
    $rowstd = $stmtstd->fetch(PDO::FETCH_ASSOC);
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
          <form id="formscore" method="post">
            <input type="text" name="classID" value="<?= $id ?>">
            <input type="text" name="classID" value="<?= $rowstd['Session'] ?>">
            <select name="subject" id="" class="form-control">
              <option value="--choose--">-Select Subject--</option>
              <?php
                $stmt = $db->conn->prepare("SELECT * FROM `subject_tbl` WHERE `class_ID` = '$id' ");
                $stmt->execute();
                $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach($subjects as $subject): ?>
                  <option value="<?= $subject['sub_ID'] ?>"><?= $subject['Subject_name'] ?></option>
              <?php endforeach ?>
            </select>
            <small class="text-danger" id="subject"></small>
            <div style="overflow: auto;">
            <table class="table table-striped" style="margin-top: 20px;">
              <small class="text-danger" id="general"></small>
              <thead>
                <tr>
                  <th>#</th>
                  <th>Fullname</th>
                  <th>Reg.No.</th>
                  <th>Test1</th>
                  <th>Test2</th>
                  <th>Test3</th>
                  <th>Exam</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $stmtstd = $db->conn->prepare("SELECT * FROM `student_tbl` WHERE `Class` = '$id' ");
                  $stmtstd->execute();
                  $students = $stmtstd->fetchAll(PDO::FETCH_ASSOC);
                  $i = 1 ;
                  foreach($students as $student):?>
                  <tr>
                    <td><?= $i ++ ?></td>
                    <td><?= $student['FirstName'].' '.$student['OtherName'].' '.$student['Surname'] ?></td>
                    <td><input type="text" value="<?php echo $student['Reg_no'] ?>" name="regno[]"></td>
                    <td><input type="number" name="ftest[]" style="width: 40px;" value="<?php //echo $rowtest['Test']; ?>"></td>
                    <td><input type="number" name="stest[]" style="width: 40px;" value="<?php //echo $rowtest['Test']; ?>"></td>
                    <td><input type="number" name="rtest[]" style="width: 40px;" value="<?php //echo $rowtest['Test']; ?>"></td>
                    <td><input type="number" name="exam[]" style="width: 40px;" value="<?php //echo $rowtest['Test']; ?>"></td>
                    <td><input type="number" name="total[]" style="width: 40px;" value="<?php //echo $rowtest['Test']; ?>"></td>
                  </tr>
                <?php endforeach ?>
              </tbody>
            </table>
            </div>
            <div>
              <button type="submit" class="btn btn-primary">submit</button>
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
  $(document).ready(function(){
    $('#formscore').on('submit', function(e){
      e.preventDefault();
      $.ajax({
        url: 'model/addexam.php',
        dataType: 'JSON',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response){
          if(! response.status){
            // alert('empty');
            $('#subject').text(response.errors.subject || '');
            $('#general').text(response.errors.general || '');
          }else{
            alert('fill');
          }
        },
        error: function(error){
          alert('errors:' + error)
        }
      })
    })
  })
</script>


