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

						<!-- Page Heading -->
						<!-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
								<h1 class="h3 mb-0 text-gray-800"></h1>
							<button class="btn btn-primary" type="button" data-target="#modalUser" data-toggle="modal">Add User</button>
						</div> -->

						<!-- Content Row -->
                <form method="POST" id="createStudent" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom01">First name</label>
                            <input type="text" class="form-control" name="fname" id="validationCustom01" placeholder="First name">
                            <div class="text-danger" id="errorFirstname"></div>
                            <small class="text-danger" id="errorFirstname"></small>               
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom02">Other Name</label>
                            <input type="text" name="mname" class="form-control" value=" "  placeholder="Other Name">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom02">Surname</label>
                            <input type="text" name="sname" class="form-control" id="validationCustom02" placeholder="Surname">
                            <small class="text-danger" id="sname"></small>
                        </div>                
                    </div>
                    
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom01">Email</label>
                            <input type="email" name="email" class="form-control" id="validationCustom01" placeholder="example@email.com">
                            <small class="text-danger" id="email"></small>              
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom02">Phone</label>
                            <input type="number" name="phone" class="form-control"  placeholder="080123456789">
                            <small class="text-danger" id="phone"></small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom02">Gender</label>
                            <select name="gender" class="form-control">
                                <option>--choose--</option>
                                <?php
                                    $stmt = $db->conn->prepare('SELECT * FROM `gender_tbl`');
                                    $stmt->execute();
                                    $gender = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach($gender as $sex): ?>
                                    <option value="<?= $sex['id'] ?>"><?= $sex['Gender'] ?></option>
                                <?php endforeach ?>
                            </select>
                            <small class="text-danger" id="gender"></small>
                        </div>                
                    </div>

                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom01">Class</label>
                            <select name="class" id="class" class="form-control">
                            <option value="--choose--">--choose--</option>
                            <?php
                                $stmtclass = $db->conn->prepare("SELECT * FROM `class_tbl` WHERE `class_tbl`.`Status` = 'Active' ");
                                $stmtclass->execute();
                                $classes = $stmtclass->fetchAll(PDO::FETCH_ASSOC);
                                foreach($classes as $class):?>
                                <option value="<?= $class['class_ID'] ?>"><?= $class['Class_Name'] ?></option>
                            <?php endforeach ?>
                            </select>
                            <small class="text-danger" id="class"></small>               
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom02">Registration No.</label>
                            <input type="text" id="reg_no" name="reg_no" class="form-control" readonly>
                            <small class="text-danger" id="studentnumber"></small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom02">Session</label>
                            <select name="stdsession" id="validationCustom02" class="form-control">
                                <option value="--choose--">--choose--</option>
                                <?php
                                    $stmtsession = $db->conn->prepare('SELECT * FROM `session_tbl` ORDER BY `s_ID` DESC LIMIT 1');
                                    $stmtsession->execute();
                                    $sessions = $stmtsession->fetchAll(PDO::FETCH_ASSOC);
                                    foreach($sessions as $sess):?>
                                    <option value="<?= $sess['s_ID'] ?>"><?= $sess['Session'] ?></option>
                                <?php endforeach ?>
                            </select>
                            <small class="text-danger" id="stdsession"></small>
                        </div>                
                    </div>

                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom01">Term</label>
                            <select name="term" id="" class="form-control">
                            <option value="--choose--">--choose--</option>
                            <?php
                                $stmtterm = $db->conn->prepare('SELECT * FROM `term_tbl`');
                                $stmtterm->execute();
                                $terms = $stmtterm->fetchAll(PDO::FETCH_ASSOC);
                                foreach($terms as $term):?>
                                <option value="<?= $term['id'] ?>"><?= $term['term'] ?></option>
                            <?php endforeach ?> 
                            </select>
                            <small class="text-danger" id="term"></small>              
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom02">Address</label>
                            
                            <input type="text" name="address" id="" class="form-control">
                            <small class="text-danger" id="address"></small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom02">DOB</label>
                            <input type="date" name="dob" id="" class="form-control">
                        </div>                
                    </div>

                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="">Passport</label>
                            <input type="file" name="image" onchange="previewFile(this);" style="display: block;" class="form-contro">
                            <small class="text-danger" id="image"></small>
                        </div> 
                        <div class="col-md-4 mb-3">
                            <img id="previewImg" src="../../img/img__nopic_avatar6.jpg" alt="Placeholder" style="height: 40%;width:25%" class="form-control">
                        </div>
                    </div>


                    <button class="btn btn-primary" name="newstudent" type="submit">Submit form</button>
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
    $('#class').on('change', function () {
        const classId = $(this).val();

        if (classId) {
            $.ajax({
                url: 'model/ajax/regno.php',
                type: 'POST',
                data: { class_id: classId },
                success: function (response) {
                    $('#reg_no').val(response);
                },
                error: function () {
                    alert('Failed to fetch registration number.');
                },
            });
        } else {
            $('#reg_no').val('');
        }
    });
    }); 
</script>

<script>
    $(document).ready(function () {
        $('#createStudent').on('submit', function (e) {
            e.preventDefault();
            $('small.text-danger').text('');
            var formData = new FormData(this);
            $.ajax({
                url: 'model/students/create.php',
                data: formData,//$(this).serialize(),
                contentType: false,
                processData: false,
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
                        $('#createStudent')[0].reset();
                    }
                },
                error: function (xhr, status, error) {
                    alert('Error: ' + error);
                }
            });
        });
    });
</script>

<script>
	$(document).ready(function(){
    $('#userForm').on('submit', function(e){
			e.preventDefault();
			$.ajax({
				url: 'model/user.form.php',
				dataType: 'JSON',
				data: $(this).serialize(),
				type: 'POST',
				success: function(response){
					if(response.status === false){
						$('#errorFname').text(response.errors.fname || '');
						$('#errorEmail').text(response.errors.email || '');
						$('#errorPhone').text(response.errors.phone || '');
						$('#errorUnit').text(response.errors.unit || '');
						$('#errorRole').text(response.errors.role || '');
						$('#errorEmail').text(response.errors.email || response.errors.emailExist || ''); 
						$('#errorPhone').text(response.errors.phone || response.errors.phoneExist || '');
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
							title: response.success.success//"Signed in successfully"
						});
						$('#usersTable').DataTable().ajax.reload();
						$('#errorFname, #errorEmail, #errorPhone, #errorUnit, #errorRole, #errorEmail,  #errorPhone, #modalUser').text('');
						$('#modalUser').modal('hide');
						$('#userForm')[0].reset();
					}
				},
					error: function(xhr, status, error){
						alert('Error: ' + xhr.status + ' - ' + error);
					}
			});
    });
});
</script>
<script>
    function previewFile(input){
        var file = $("input[type=file]").get(0).files[0];
 
        if(file){
            var reader = new FileReader();
 
            reader.onload = function(){
                $("#previewImg").attr("src", reader.result);
            }
 
            reader.readAsDataURL(file);
        }
    }
</script>

