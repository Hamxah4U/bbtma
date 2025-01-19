<?php
		require 'partials/security.php';
    require 'partials/header.php';
		require 'model/Database.php';
?>

    <!-- Page Wrapper -->
<div id="wrapper">
	<!-- Sidebar -->
	<?php require 'partials/sidebar.php' ?>
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- Topbar -->
        <?php  require 'partials/nav.php';?>

				<!-- Begin Page Content -->
				<div class="container-fluid">
					<div class="d-sm-flex align-items-center justify-content-between mb-4">
						<h1 class="h3 mb-0 text-gray-800"></h1>
						<a href="#" clas="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i clas="fas fa-download fa-sm text-white-50"></i></a>
					</div>
						<!-- Content Row -->
					<div class="container mt-4">
						<form id="adminReport">
							<div class="row mb-3">
								<div class="col-md-2">
									<label for="unit">Session:</label>
								</div>
								<div class="col-md-4">
									<select name="session" id="session" class="form-control">
										<option value="--choose--">--choose--</option>
										<?php
											$stmtSession = $db->conn->prepare('SELECT * FROM `session_tbl` ORDER BY s_ID DESC');
											$stmtSession->execute();
											$sessions = $stmtSession->fetchAll(PDO::FETCH_ASSOC);
											foreach($sessions as $session):?>
											<option value="<?= $session['s_ID'] ?>"><?= $session['Session'] ?></option>
										<?php endforeach ?>
									</select>
									<small class="text-danger" id="errorSession"></small>
								</div>
								<div class="col-md-2">
									<label for="product">Term:</label>
								</div>
								<div class="col-md-4">
									<select name="term" id="term" class="form-control">
										<option value="--choose--">--choose--</option>
										<?php
											$stmtTerm = $db->conn->prepare('SELECT * FROM `term_tbl`');
											$stmtTerm->execute();
											$terms = $stmtTerm->fetchAll(PDO::FETCH_ASSOC);
											foreach($terms as $term) :?>
											<option value="<?= $term['id'] ?>"><?= $term['term'] ?></option>
										<?php endforeach ?>
									</select>
									<small class="text-danger" id="errorTerm"></small>
								</div>
							</div>

							<div class="row mb-3">
								<div class="col-md-2">
									<label for="sdate">Class:</label>
								</div>
								<div class="col-md-4">
									<select name="stdclass" id="" class="form-control">
										<option value="--choose--">--choose--</option>
										<?php
											$stmtClass = $db->conn->prepare('SELECT * FROM `class_tbl` WHERE `Status` = "Active" GROUP BY `Class_Name` ORDER BY `Class_Name`');
											$stmtClass->execute();
											$classes = $stmtClass->fetchAll(PDO::FETCH_ASSOC);
											foreach($classes as $class) :?>
											<option value="<?= $class['class_ID'] ?>"><?= $class['Class_Name'] ?></option>
										<?php endforeach ?>
									</select>
									<small class="text-danger" id="errorClass"></small>
								</div>
								<div class="col-md-2">
									<label for="edate">Student's ID:</label>
								</div>
								<div class="col-md-4">
									<input type="text" name="stidentregno" class="form-control" id="edate" placeholder="Enter 'all' for all students or a registration number for a single student">
									<small class="text-danger" id="errorRegNo"></small>
								</div>
							</div>							

							<div class="row">
								<div class="col-md-6">
									<!-- <button type="button" id="btn2" class="btn btn-danger" onclick="PrintDoc()">
										<i class="icofont-print"></i> Print
									</button> -->
								</div>
								<div class="col-md-6 text-end">
									<input type="submit" class="btn btn-primary" value="Search">
								</div>
							</div>
						</form>
					</div>

					<div id="reportResults" class="mt-4"></div>
				</div>
      </div>

<?php require 'partials/footer.php'; ?>
<script>
	$(document).ready(function(){
    $('#adminReport').on('submit', function(e){
        e.preventDefault();
        $('.text-danger').text('');
        $.ajax({
            url: 'model/student.report.php',
            type: 'POST',
            dataType: 'JSON',
            data: $(this).serialize(),
            success: function(response){
                if(! response.status){
                    $('#errorSession').text(response.errors.session || '');
                    $('#errorTerm').text(response.errors.term || '');
                    $('#errorClass').text(response.errors.class || '');
                    $('#errorRegNo').text(response.errors.studentregno || '');
                } else {
									setTimeout(function(){
										window.location.href = response.success.redirect
									}, 1000)
                }
            },
            error: function(xhr, status, error){
                alert('Error: ' + xhr + status + error);
            }
        })
    })
	});
</script>