<?php
  require './model/Database.php';
  require 'views/partials/security.php';
	require 'views/partials/header.php';

  if(isset($_GET['editstd'])){
    $id = $_GET['editstd'];
    $stmt = $db->conn->prepare('SELECT * FROM `student_tbl` WHERE `stu_ID` = :stu_ID');
    $stmt->bindParam(':stu_ID', $id);
    $stmt->execute();
    $rowstd = $stmt->fetch(PDO::FETCH_ASSOC);
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

						<!-- Page Heading -->
						<!-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
								<h1 class="h3 mb-0 text-gray-800"></h1>
							<button class="btn btn-primary" type="button" data-target="#modalUser" data-toggle="modal">Add User</button>
						</div> -->

						<!-- Content Row -->
                <form method="POST" id="updateStudent" enctype="multipart/form-data">
                  <input type="hidden" name="stdID" id="" value="<?= $rowstd['stu_ID'] ?>">
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom01">First name</label>
                            <input value="<?= $rowstd['FirstName'] ?>" type="text" class="form-control" name="fname" id="validationCustom01" placeholder="First name">
                            <div class="text-danger" id="errorFirstname"></div>
                            <small class="text-danger" id="errorFirstname"></small>               
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom02">Other Name</label>
                            <input value="<?= $rowstd['OtherName'] ?>" type="text" name="mname" class="form-control" value=" "  placeholder="Other Name">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom02">Surname</label>
                            <input value="<?= $rowstd['Surname'] ?>" type="text" name="sname" class="form-control" id="validationCustom02" placeholder="Surname">
                            <small class="text-danger" id="sname"></small>
                        </div>                
                    </div>
                    
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom01">Email</label>
                            <input value="<?= $rowstd['Email'] ?>" type="email" name="email" class="form-control" id="validationCustom01" placeholder="example@email.com">
                            <small class="text-danger" id="email"></small>              
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom02">Phone</label>
                            <input value="<?= $rowstd['Phone'] ?>" type="number" name="phone" class="form-control"  placeholder="080123456789">
                            <small class="text-danger" id="phone"></small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom02">Gender</label>
                            <select name="gender" class="form-control">                                
                                <?php
                                    $stmt = $db->conn->prepare('SELECT * FROM `gender_tbl`');
                                    $stmt->execute();
                                    $gender = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach($gender as $sex):
                                      $selected = (isset($rowstd['Gender']) && $rowstd['Gender'] == $sex['id']) ? 'selected' : '';
                                    ?>                                    
                                    <option value="<?= $sex['id'] ?>" <?= $selected ?>> <?= $sex['Gender'] ?> </option>
                                <?php endforeach ?>
                            </select>
                            <small class="text-danger" id="gender"></small>
                        </div>                
                    </div>

                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom01">Class</label>
                            <select name="class" id="class" class="form-control">
                            <!-- <option value="--choose--">--choose--</option> -->
                            <?php
                                $stmtclass = $db->conn->prepare("SELECT * FROM `class_tbl` WHERE `class_tbl`.`Status` = 'Active' ");
                                $stmtclass->execute();
                                $classes = $stmtclass->fetchAll(PDO::FETCH_ASSOC);
                                foreach($classes as $class):
                                  $selected = (isset($rowstd['Class']) && $rowstd['Class'] == $class['class_ID']) ? 'selected' : '';
                                ?>                                
                                <option value="<?= $class['class_ID'] ?>" <?= $selected ?> > <?= $class['Class_Name'] ?> </option>
                            <?php endforeach ?>
                            </select>
                            <small class="text-danger" id="class"></small>               
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom02">Registration No.</label>
                            <input value="<?= $rowstd['Reg_no'] ?>" type="text" id="reg_no" name="reg_no" class="form-control" readonly>
                            <small class="text-danger" id="studentnumber"></small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom02">Session</label>
                            <select name="stdsession" id="validationCustom02" class="form-control">                                
                                <?php
                                    $stmtsession = $db->conn->prepare('SELECT * FROM `session_tbl` LIMIT 1  ');
                                    $stmtsession->execute();
                                    $sessions = $stmtsession->fetchAll(PDO::FETCH_ASSOC);
                                    foreach($sessions as $sess):
                                      $selected = (isset($rowstd['Session']) && $rowstd['Session'] == $sess['s_ID']) ? 'selected' : '';
                                    ?>
                                    <option value="<?= $sess['s_ID'] ?>" <?= $selected ?> > <?= $sess['Session'] ?> </option>
                                <?php endforeach ?>
                            </select>
                            <small class="text-danger" id="stdsession"></small>
                        </div>                
                    </div>

                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom01">Term</label>
                            <select name="term" id="" class="form-control">
                            <!-- <option value="--choose--">--choose--</option> -->
                            <?php
                                $stmtterm = $db->conn->prepare('SELECT * FROM `term_tbl`');
                                $stmtterm->execute();
                                $terms = $stmtterm->fetchAll(PDO::FETCH_ASSOC);
                                foreach($terms as $term):
                                $selected = (isset($rowstd['Term']) && $rowstd['Term'] == $term['id']) ? 'selected' : '';
                                ?>
                                <option value="<?= $term['id'] ?>" <?= $selected ?>  > <?= $term['term'] ?> </option>
                            <?php endforeach ?> 
                            </select>
                            <small class="text-danger" id="term"></small>              
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom02">Address</label>                            
                            <input value="<?= $rowstd['Address'] ?>" type="text" name="address" id="" class="form-control">
                            <small class="text-danger" id="address"></small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom02">DOB</label>
                            <input value="<?= $rowstd['DOB'] ?>" type="date" name="dob" id="" class="form-control">
                        </div>                
                    </div>

                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="">Passport</label>
                            <input value="<?= $rowstd['Passphort'] ?>" type="file" name="image" onchange="previewFile(this);" style="display: block;" class="form-contro">
                            <small class="text-danger" id="image"></small>
                        </div> 
                        <div class="col-md-4 mb-0">
                            <img value="<?= $rowstd['Passphort'] ?>" id="previewImg" src="../../model/students/uploads/<?= $rowstd['Passphort'] ?>" alt="Placeholder" style="height: 30%;width:35%" class="form-control">
                        </div>

                        <div class="col-md-4 col-12 mb-0">
                            <button class="btn btn-info mt-3 w-100" name="newstudent" type="submit">Update form</button>
                        </div>
                    </div>

                   
                
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
    $('#updateStudent').on('submit', function (e) {
        e.preventDefault();
        $('small.text-danger').text('');
        var formData = new FormData(this);
        $.ajax({
            url: 'model/students/update.student.php',
            data: formData,//$(this).serialize(),
            contentType: false,
            processData: false,
            dataType: 'JSON',
            type: 'POST',
            success: function (response) {
                if (!response.status) {                    
                    $.each(response.errors, function (key, value) {
                        $(`[name="${key}"]`).siblings('small.text-danger').text(value);
                        $(`[name="${key}"]`).closest('.form-floating').find('small.text-danger').text(value);
                    });
                } else {
                    // alert(response.success.message);
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
                        icon: "info",
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

<script>
	
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

