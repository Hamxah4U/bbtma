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
            <?php require 'views/partials/nav.php'; ?>
            
            <!-- Begin Page Content -->
            <div class="container-fluid">         
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

                    <!-- <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="">Passport</label>
                            <input type="file" name="image" onchange="previewFile(this);" style="display: block;" class="form-contro">
                            <small class="text-danger" id="image"></small>
                        </div> 
                        <div class="col-md-4 mb-3">
                            <img id="previewImg" src="../../img/img__nopic_avatar6.jpg" alt="Placeholder" style="height: 40%;width:25%" class="form-control">
                        </div>
                    </div> -->

                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="camera-icon">
                                <i class="fas fa-camera" id="camera-icon" style="font-size: 48px; cursor: pointer;"></i>
                            </label>
                            <input type="file" id="camera-input" name="image" accept="image/*" capture="environment" style="display: none;">
                            <video id="camera" style="display: none; width: 150px; height: 150px; border: 1px solid #ccc;" autoplay></video>
                             <button id="capture" type="button" style="display: none;">Capture</button>
                            <small class="text-danger" id="image"></small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <img id="previewImg" src="../../img/img__nopic_avatar6.jpg" alt="Placeholder" style="height: 150px; width: 150px;" class="form-control">
                        </div>
                    </div><br/><br/>

                    <button class="btn btn-primary" name="newstudent" type="submit">Submit form</button>
                </form>
                    <!-- Content Row -->
            </div>
            <!-- /.container-fluid -->
		</div>
		<!-- End of Main Content -->
<?php require 'views/partials/footer.php'; ?>

<script>
    $(document).ready(function() {
    var video = document.querySelector("#camera");
    var captureButton = document.querySelector("#capture");
    var stream = null; // To store camera stream

    // Open camera when clicking on the camera icon
    $("#camera-icon").click(function() {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(camStream) {
                stream = camStream;
                video.srcObject = stream;
                video.style.display = 'block';
                captureButton.style.display = 'block';
            })
            .catch(function(err) {
                console.log("Camera access denied: " + err);
                alert("Please allow camera access.");
            });
    });

    // Capture image from video stream
    $("#capture").click(function() {
        var canvas = document.createElement("canvas");
        canvas.width = 150;
        canvas.height = 150;
        var context = canvas.getContext("2d");
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        var dataURL = canvas.toDataURL("image/png");

        $("#previewImg").attr("src", dataURL);

        // Convert dataURL to Blob
        var blob = dataURLToBlob(dataURL);
        var file = new File([blob], "captured_image.png", { type: "image/png" }); // Create File object

        // Create a new DataTransfer object and append the file
        var dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        $("#camera-input")[0].files = dataTransfer.files; // Assign the files property

        // Stop camera stream
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }

        video.style.display = 'none';
        captureButton.style.display = 'none';
    });

    // Handle form submit event
    $("#createStudent").submit(function(event) {
        event.preventDefault(); // Prevent the default form submission
        $('small.text-danger').text('');

        // Create FormData and append the file
        var formData = new FormData(this);

        // Send FormData using AJAX
        $.ajax({
            url: "model/students/create.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response.status) {
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
                        title: response.success.success
                    });
                    $("#createStudent")[0].reset();
                    $("#previewImg").attr("src", "../../img/img__nopic_avatar6.jpg");
                } else {
                    if (response.errors) {
                        for (const key in response.errors) {
                            if (Object.hasOwnProperty.call(response.errors, key)) {
                                $(`#${key}`).text(response.errors[key]);
                            }
                        }
                    } else {
                        alert("An error occurred. Please check the console.");
                    }
                }
            },
            error: function(error) {
                console.error(error);
                alert("An error occurred during the AJAX request.");
            }
        });
    });

    // Helper function to convert dataURL to Blob
    function dataURLToBlob(dataURL) {
        var parts = dataURL.split(';base64,');
        var contentType = parts[0].split(':')[1];
        var raw = window.atob(parts[1]);
        var rawLength = raw.length;
        var uInt8Array = new Uint8Array(rawLength);
        for (var i = 0; i < rawLength; ++i) {
            uInt8Array[i] = raw.charCodeAt(i);
        }
        return new Blob([uInt8Array], { type: contentType });
    }
});

</script>

<script>
    function previewFile(input) {
        var file = $(input).get(0).files[0];

        if (file) {
            var reader = new FileReader();

            reader.onload = function() {
                $("#previewImg").attr("src", reader.result);
            }

            reader.readAsDataURL(file);
        }
    }
</script>

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
    /* $(document).ready(function () {
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
    }); */
</script>












<script>
	/* $(document).ready(function(){
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
}); */
</script>

<script>
    /* function previewFile(input){
        var file = $("input[type=file]").get(0).files[0];
 
        if(file){
            var reader = new FileReader();
 
            reader.onload = function(){
                $("#previewImg").attr("src", reader.result);
            }
 
            reader.readAsDataURL(file);
        }
    } */
</script>

