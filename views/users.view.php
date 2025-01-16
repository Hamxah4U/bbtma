<?php
	require 'partials/security.php';
	require 'partials/header.php';
	require 'classes/Users.class.php';
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Page Wrapper -->
<div id="wrapper">
    <!-- Sidebar -->
    <?php require 'partials/sidebar.php' ?>

    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
  <div id="content-wrapper" class="d-flex flex-column">

		<!-- Main Content -->
		<div id="content">

				<!-- Topbar -->
				<?php
						require 'partials/nav.php';
				?>
				<!-- End of Topbar -->

				<!-- Begin Page Content -->
				<div class="container-fluid">

						<!-- Page Heading -->
						<div class="d-sm-flex align-items-center justify-content-between mb-4">
								<h1 class="h3 mb-0 text-gray-800"></h1>
							<button class="btn btn-primary" type="button" data-target="#modalUser" data-toggle="modal">Add User</button>
						</div>

						<!-- Content Row -->
						<table class="table table-striped" id="usersTable">
							<thead>
								<tr>
									<th>#</th>
									<th>Fullname</th>
									<th>Email</th>
									<th>Phone</th>
									<th>Role</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
						<!-- Content Row -->
				</div>
				<!-- /.container-fluid -->

		</div>
		<!-- End of Main Content -->

<?php
    require 'partials/footer.php';
?>

<!-- Modal -->
<div class="modal fade" id="modalUser" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title text-primary"><strong>User Registration Window</strong></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true" class="text-danger"><strong>&times;</strong></span>
					</button>
			</div>
			<div class="modal-body">
				<form id="userForm">

					<div class="form-group">
						<label for="my-input">Fullname</label>
						<input id="fname" class="form-control" type="text" name="fname">
						<small class="text-danger" id="errorFname"></small>
					</div>
					
					<div class="form-group">
						<label for="my-input">Email</label>
						<input id="email" class="form-control" type="email" name="email">
						<small class="text-danger" id="errorEmail"></small>
					</div>

					<div class="form-group">
						<label for="my-input">Phone</label>
						<input id="phone" class="form-control" type="number" name="phone">
						<small class="text-danger" id="errorPhone"></small>
					</div>

					<div class="form-group">
						<label for="my-input">Role</label>
						<select name="role" id="" class="form-control">
							<option value="--choose--"></option>
							<?php
								$stmt = $db->conn->prepare('SELECT * FROM `role_tbl`');
								$stmt->execute();
								$rowrole = $stmt->fetchAll(PDO::FETCH_ASSOC);
								foreach($rowrole as $role): ?>
								<option value="<?= $role['id'] ?>"> <?= $role['role'] ?> </option>
							<?php endforeach ?>
						</select>
						<small class="text-danger" id="errorRole"></small>
					</div>

					<div class="form-group">
						<label for="my-input">Address</label>
						<textarea name="address" id="" class="form-control"></textarea>
						<small class="text-danger" id="errorUnit"></small>
					</div>

					<button type="submit" class="btn btn-primary">Save</button>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		$('#usersTable').DataTable({
			ajax:{
				url: 'model/user.table.php',
				dataSrc: '',
			},
			columns:[
				{'data': null, render:(data, type, row, meta) => meta.row + 1},
				{'data': 'Fullname'},
				{'data': 'Email'},
				{'data': 'Phone'},
				{'data': 'role'},
				{'data': 'Status'},
				{'data': null, "render": function(data, type, row)
					{
						return `<button type="button" class="btn btn-info"><span class="fas fa-fw fa-edit"></span></button>
						<button type="button" class="btn btn-danger"><span class="fas fa-fw fa-refresh"></span></button>`
					}
				}
			]
		});
	});
</script>

<script>
	/* $(document).ready(function () {
		$('#userForm').on('submit', function (e) {
				e.preventDefault();
				$.ajax({
						url: 'user.form.php',
						dataType: 'JSON',
						data: $(this).serialize(),
						type: 'POST',
						success: function (response) {
								if (response.status === false) {
										$('#errorFname').text(response.errors.fname || '');
										$('#errorEmail').text(response.errors.email || response.errors.emailExist || '');
										$('#errorPhone').text(response.errors.phone || response.errors.phoneExist || '');
										$('#errorRole').text(response.errors.role || '');
										$('#errorUnit').text(response.errors.unit || '');
								} else {
										Swal.fire({
												icon: 'success',
												title: response.success.success,
												toast: true,
												position: 'top-end',
												showConfirmButton: false,
												timer: 2000
										});
										$('#userForm')[0].reset();
										$('#modalUser').modal('hide');
										$('#usersTable').DataTable().ajax.reload();
								}
						},
						error: function (xhr, status, error) {
								alert('Error: ' + xhr.status + ' - ' + error);
						}
				});
		});
  }); */


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
	document.getElementById("copyButton").addEventListener("click", function () {			
		const textToCopy = document.getElementById("textToCopy");		
		textToCopy.select();
		textToCopy.setSelectionRange(0, 99999); 
		navigator.clipboard.writeText(textToCopy.value).then(() => {
				//alert("Text copied to clipboard!");
		}).catch(err => {
				console.error("Failed to copy text: ", err);
		});
	});
</script>
