<?php
  require './model/Database.php';
  require 'views/partials/security.php';
	require 'views/partials/header.php';

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
          <form method="POST" id="updatestaff" enctype="multipart/form-data">
            <input type="hidden" name="stdID" id="" value="<?= $_SESSION['userID'] ?>">
              <div class="form-row">
                  <div class="col-md-4 mb-3">
                      <label for="validationCustom01">Fullname</label>
                      <input value="<?= $_SESSION['fname'] ?>" type="text" class="form-control" name="fname" id="validationCustom01" placeholder="First name">
                      <small class="text-danger" id="errorFirstname"></small>               
                  </div>
                  <div class="col-md-4 mb-3">
                      <label for="validationCustom02">Email</label>
                      <input readonly value="<?= $_SESSION['email'] ?>" type="text" name="email" class="form-control">
                      <small class="text-danger" id="email"></small>
                  </div>
                  <div class="col-md-4 mb-3">
                      <label for="validationCustom02">Phone</label>
                      <input value="<?= $_SESSION['phone'] ?>" type="number" name="phone" class="form-control">
                      <small class="text-danger" id="phone"></small>
                  </div>                
              </div>
              
              <div class="form-row">
                  <div class="col-md-4 mb-3">
                      <label for="validationCustom01">Gender</label>
                      <select name="gender" class="form-control">                                
                          <?php
                              $stmt = $db->conn->prepare('SELECT * FROM `gender_tbl`');
                              $stmt->execute();
                              $gender = $stmt->fetchAll(PDO::FETCH_ASSOC);
                              foreach($gender as $sex):
                                $selected = (isset($_SESSION['gender']) && $_SESSION['gender'] == $sex['id']) ? 'selected' : '';
                              ?>                                    
                              <option value="<?= $sex['id'] ?>" <?= $selected ?>> <?= $sex['Gender'] ?> </option>
                          <?php endforeach ?>
                      </select>
                      <small class="text-danger" id="email"></small>              
                  </div>
                  <div class="col-md-4 mb-3">
                      <label for="validationCustom02">Role</label>
                      <?php
                        $roleuser = $_SESSION['role'];
                        $rolesql = $db->conn->prepare('SELECT * FROM role_tbl WHERE `id` = :role ');
                        $rolesql->execute([':role' => $roleuser]);
                        $rolerow = $rolesql->fetch(PDO::FETCH_ASSOC);
                      ?>
                      <input readonly value="<?= $rolerow['role'] ?>" type="text" name="phone" class="form-control" >
                      <small class="text-danger" id="phone"></small>
                  </div>
                  <div class="col-md-4 mb-3">
                      <label for="validationCustom02">Address</label>
                        <textarea name="address" id="" class="form-control" ><?= $_SESSION['address'] ?></textarea>
                      <small class="text-danger" id="address"></small>
                  </div>                
                </div>

              <button class="btn btn-info" name="newstudent" type="submit">Update form</button>
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
    $(document).ready(function() {
    $('#updatestaff').on('submit', function(e) {
        e.preventDefault();
        $('small.text-danger').text(''); 

        $.ajax({
            url: 'model/updateprofile.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (!response.status) {
                    $.each(response.errors, function(key, value) {
                        $('#' + key).text(value);
                    });
                } else {
                    // alert('Profile updated successfully!');
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
            error: function() {
                alert('An error occurred while updating the profile.');
            }
        });
    });
});

/* alert(response.success.message);
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
}); */

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

