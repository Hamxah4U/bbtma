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

						<!-- Content Row -->
                <form method="POST" id="calendar">
                  <?php
                    $result = $db->conn->prepare('SELECT `session_tbl`.`Session` AS ss FROM `student_tbl` JOIN session_tbl ON `session_tbl`.`s_ID` = `student_tbl`.`Session` LIMIT 1');//@mysqli_query($conn, $sql);
                    $result->execute();
                    $rowsession = $result->fetch();

                    $sqlterm = $db->conn->prepare('SELECT `term_tbl`.`term` AS tt FROM `student_tbl`JOIN `term_tbl` ON `term_tbl`.`id` = `student_tbl`.`Term` LIMIT 1');
                    $sqlterm->execute();
                    $rowterm = $sqlterm->fetch();
                  ?>
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom01">Current Session</label>
                            <input readonly type="text" value="<?= $rowsession['ss'] ?>" class="form-control" name="fname" id="validationCustom01" placeholder="First name">
                            <div class="text-danger" id="errorFirstname"></div>
                            <small class="text-danger" id="errorFirstname"></small>               
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom02">Current Term</label>
                            <input readonly value="<?= $rowterm['tt'] ?>" type="text" name="mname" class="form-control" value=" "  placeholder="Other Name">
                        </div>             
                    </div>
                    
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom01">Activate New Session</label>
                            <select name="newsession" id="" class="form-control">
                              <option value="--choose--"></option>
                              <?php
                                 $stmt = $db->conn->prepare('SELECT * FROM `session_tbl` ORDER BY `session_tbl`.`s_ID` DESC LIMIT 1');
                                 $stmt->execute();
                                 $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                 foreach($sessions as $session): ?>
                                 <option value="<?= $session['s_ID'] ?>"><?= $session['Session'] ?></option>
                              <?php endforeach ?>
                            </select>
                            <small class="text-danger" id="newsession"></small>              
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom02">Activate New Term</label>
                            <select name="newterm" id="" class="form-control">
                              <option value="--choose--"></option>
                              <?php
                                $stmt = $db->conn->prepare('SELECT * FROM `term_tbl`');
                                $stmt->execute();
                                $terms = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                foreach($terms as $term): ?>
                                <option value="<?= $term['id'] ?>"><?= $term['term'] ?></option>
                              <?php endforeach ?>
                            </select>
                            <small class="text-danger" id="newterm"></small>
                        </div>              
                    </div>

                    <button class="btn btn-primary" name="newstudent" type="submit">Activate Academic Calendar</button>
                </form>
						<!-- Content Row -->
				</div>
				<!-- /.container-fluid -->

		</div>
		<!-- End of Main Content -->

<?php
    require 'views/partials/footer.php';
?>


<script>
        $(document).ready(function () {
        $('#calendar').on('submit', function (e) {
            e.preventDefault();
            $('small.text-danger').text('');
            var formData = new FormData(this);
            $.ajax({
                url: 'model/calender.form.php',
                data: $(this).serialize(),
                dataType: 'JSON',
                type: 'POST',
                success: function (response) {
                    if (!response.status) {                    
                        $.each(response.errors, function (key, value) {
                            $(`[name="${key}"]`).siblings('small.text-danger').text(value);
                        });
                    } else {
                        //alert(response.success.message);
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
							title: response.success.message//"Signed in successfully"
						});
                    }
                },
                error: function (xhr, status, error) {
                    alert('Error: ' + error);
                }
            });
        });
    });
</script>
