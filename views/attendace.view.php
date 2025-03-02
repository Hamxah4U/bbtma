<?php
	require 'partials/security.php';
	require 'partials/header.php';
	require 'classes/Users.class.php';
?>
<style>
    .border-danger {
        border: 1px solid red !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Page Wrapper -->
<div id="wrapper">
    <!-- Sidebar -->
    <?php require 'partials/sidebar.php' ?>

    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
  <div id="content-wrapper" class="d-flex flex-column">

		<!-- Main Content -->
		<div id="letter__">

				<!-- Topbar -->
				<?php require 'partials/nav.php'; ?>
				<div class="container-fluid">          
          <form id="classattendance">
              <label for="">List of Class</label>
              <select name="cclass" id="selectclass" class="form-control">
                  <option value="--choose--">--choose--</option>
                  <?php
                  $sql = $db->conn->prepare('SELECT * FROM `class_tbl` WHERE `Status` = "Active" ');
                  $sql->execute();
                  $classes = $sql->fetchAll(PDO::FETCH_ASSOC);
                  foreach ($classes as $class) : ?>
                      <option value="<?= $class['class_ID'] ?>"><?= $class['Class_Name'] ?></option>
                  <?php endforeach ?>
              </select>
              <small class="text-danger" id="cclass"></small>
              <br><br>
              <div id="letter">
              <table class="table table-striped" id="usersTable">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Fullname</th>
                      <th>Attendance</th>
                      <!-- <th>Admission</th> -->
                      <th>Gender</th>
                      <th>Class</th>
                    </tr>
                  </thead>
                  <tbody>
                      
                  </tbody>
              </table>
              </div>

              <div class="form-group row">
                  <div class="col-lg-8">
                      <select name="category" id="classSelect" class="form-control">
                          <option value="--choose--">--choose--</option>
                          <option value="Morning">Morning</option>
                          <option value="Afternoon">Afternoon</option>
                          <option value="Evening">Evening</option>
                      </select>
                      <small class="text-danger" id="classp"></small>
                  </div>
                  <div class="col-lg-4">
                      <button class="btn btn-primary form-control" name="promotedTo" type="submit">Save</button>
                  </div>
              </div>
          </form>
				</div>
				<!-- /.container-fluid -->

		</div>
		<!-- End of Main Content -->

<?php    require 'partials/footer.php'; ?>

<script>
  $(document).ready(function(){
    $('#classattendance').on('submit', function(e){
        e.preventDefault();
        $('.text-danger').text('');
        $('select[name="attendance[]"]').removeClass('border-danger');
        $.ajax({
            url: 'model/attendace.php',
            data: $(this).serialize(),
            type: 'POST',
            dataType: 'JSON',
            success: function(response){
                if(!response.status){
                    $.each(response.errors, function (key, value) {
                      $('#' + key).text(value);
                    });

                    if (response.attendanceErrors) {
                        $.each(response.attendanceErrors, function(key, value) {
                            if (value) {
                                $('select[name="attendance[]"]').eq(key).addClass('border-danger');
                            }
                        });
                    }

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
                        title: response.success.message//"Signed in successfully"
                    });
                }
            },
            error: function(xhr, status, error){
                alert('Error: ' + status + '\nMessage: ' + error);
            }
        })
    })
  })
</script>


<script>
  $(document).ready(function () {
    $('#selectclass').on('change', function () {
      let selectedClass = $(this).val();
      if(selectedClass !== "--choose--") {
        $('#usersTable tbody').empty();

        $.ajax({
          url: 'model/promotion.table.php',
          method: 'POST',
          data: { class: selectedClass },
          dataType: 'json',
          success: function (response) {
            if (response.error) {
                alert(response.error);
                return;
            }
            let counter = 1;
            response.forEach(student => { 
              let row = `
                <tr>
                  <td>${counter++}</td>
                  <td>${student.FirstName + ' '+ student.OtherName + ' ' + student.Surname}</td>
                  <td>
                    <input type="hidden" name="regno" value="${student.Reg_no}">
                    <input type="hidden" name="myID[]" value="${student.stu_ID}">
                    <input type="hidden" name="stdclass[]" value="${student.Class}">
                    <input type="hidden" name="session[]" value="${student.Session}">
                    <input type="hidden" name="term[]" value="${student.Term}">
                    <select name="attendance[]" id="att">
                      <option value="--select--">--select--</option>
                      <option value="1">Present</option>
                      <option value="0">Absent</option>
                    </select>
                  </td>
                  <td>${student.Gender}</td>
                  <td>${student.Class_Name}</td>
                </tr>
              `;
              $('#usersTable tbody').append(row);
            });
            },
            error: function () {
              alert('Failed to fetch students. Please try again.');
            }
        });
      }else {
        alert('Please select a valid class.');
      }
    });
  });
</script>

