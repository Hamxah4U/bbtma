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
									<label for="unit">Unit:</label>
								</div>
								<div class="col-md-4">
									<input type="text" name="unit" class="form-control" placeholder="Type 'all' for all dpt">
									<small class="text-danger" id="errorUnit"></small>
								</div>
								<div class="col-md-2">
									<label for="product">Product:</label>
								</div>
								<div class="col-md-4">
									<input type="text" name="product" class="form-control" placeholder="Type % for all products">
									<small class="text-danger" id="errorProduct"></small>
								</div>
							</div>

							<div class="row mb-3">
								<div class="col-md-2">
									<label for="sdate">Start Date:</label>
								</div>
								<div class="col-md-4">
									<input type="date" name="sdate" class="form-control" id="sdate">
									<small class="text-danger" id="errorF"></small>
								</div>
								<div class="col-md-2">
									<label for="edate">End Date:</label>
								</div>
								<div class="col-md-4">
									<input type="date" name="edate" class="form-control" id="edate">
									<small class="text-danger" id="errorS"></small>
								</div>
							</div>

							<div class="row mb-3">
								<div class="col-md-2">
									<label for="stime">Start Time:</label>
								</div>
								<div class="col-md-4">
									<input type="time" name="stime" class="form-control">
									<small class="text-danger" id="errorC"></small>
								</div>
								<div class="col-md-2">
									<label for="etime">End Time:</label>
								</div>
								<div class="col-md-4">
									<input type="time" name="etime" class="form-control">
									<small class="text-danger" id="errorE"></small>
								</div>
							</div>

							<div class="row mb-3">
								<div class="col-md-2">
									<label for="status">Status:</label>
								</div>
								<div class="col-md-4">
									<select name="status" id="status" class="form-control">
										<option>Paid</option>
										<option>Not-Paid</option>
									</select>
								</div>
								<div class="col-md-2">
									<label for="user">User:</label>
								</div>
								<div class="col-md-4">
								<select name="user" id="user" class="form-control">
									<option value="all">All</option>
										<?php
											$stmt = $db->query('SELECT * FROM `users_tbl` ORDER BY `Fullname` ASC');
											foreach($stmt as $users): ?>
											<option value="<?= $users['userID'] ?>"><?= $users['Fullname'] . ' ~ ' . $users['Email'] ?></option>
										<?php endforeach ?>
								</select>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<button type="button" id="btn2" class="btn btn-danger" onclick="PrintDoc()">
										<i class="icofont-print"></i> Print
									</button>
								</div>
								<div class="col-md-6 text-end">
									<input type="submit" class="btn btn-primary" value="Search">
								</div>
							</div>
						</form>
					</div>

					<div id="reportResults" class="mt-4"></div>

						<!--  for single user  <form action="" method="POST">
							<table class="table table-borderless" style="width: 100%;">
									<tr>
											<td><label for="unit">Unit:</label></td>
											<td><input type="text" name="unit" id="unit" class="form-control" placeholder="type 'all' for all departments"></td>
											<td><label for="product">&nbsp;Product:</label></td>
											<td><input type="text" name="product" id="product" class="form-control" placeholder="type % for all products"></td>
									</tr>
									<tr>
											<td><label for="sdate">Start Date:</label></td>
											<td><input type="date" name="sdate" id="sdate" class="form-control"></td>
											<td><label for="edate">&nbsp;End Date:</label></td>
											<td><input type="date" name="edate" id="edate" class="form-control"></td>
									</tr>
									<tr>
											<td><label for="stime">Start Time:</label></td>
											<td><input type="time" name="stime" id="stime" class="form-control"></td>
											<td><label for="etime">&nbsp;End Time:</label></td>
											<td><input type="time" name="etime" id="etime" class="form-control"></td>
									</tr>
									<tr>
											<td><label for="status">Status:</label></td>
											<td>
													<select name="status" id="status" class="form-control">
															<option value="Paid">Paid</option>
															<option value="Not-Paid">Not Paid</option>
													</select>
											</td>
											<td colspan="2" class="text-end">
													<input type="submit" class="btn btn-primary" name="viewreport" value="Search">
											</td>
									</tr>
									<tr>
											<td colspan="4" class="text-end">
													<button id="btn2" type="button" class="btn btn-danger" onclick="PrintDoc()">
															<span class="icofont-print"></span> Print
													</button>
											</td>
									</tr>
							</table>
						</form> -->
				</div>
      </div>


<?php
	if (isset($_POST['viewreport'])) {
	// Sanitize input using PDO prepared statements
	$unit = $_POST['unit'];
	$product = $_POST['product'];
	$startDate  = $_POST['sdate'];
	$endDate = $_POST['edate'];
	$startTime  = $_POST['stime'];
	$endTime = $_POST['etime'];
	$status = $_POST['status'];

	if (empty($unit) || empty($product) || empty($startDate) || empty($endDate) || empty($startTime) || empty($endTime)) {
			echo "<script>alert('All fields are required')</script>";
	} else {
			// Prepare the base query
			$query = "SELECT * FROM transaction_tbl t
								JOIN product_tbl p ON t.Product = p.proID
								JOIN department_tbl d ON t.tDepartment = d.deptID
								WHERE 1=1";

			// Initialize parameters array
			$params = [];

			// Add conditions dynamically based on user input
			if ($unit != "all") {
					$query .= " AND d.Department LIKE :unit";
					$params[':unit'] = "%$unit%";
			}

			if (!empty($product)) {
					$query .= " AND p.Productname LIKE :product";
					$params[':product'] = "%$product%";
			}

			// Add date and time conditions
			$query .= " AND t.TransacDate BETWEEN :startDate AND :endDate";
			$query .= " AND t.TransacTime BETWEEN :startTime AND :endTime";
			$query .= " AND t.Status = :status AND t.TrasacBy = :username";

			// Add the remaining parameters
			$params[':startDate'] = $startDate;
			$params[':endDate'] = $endDate;
			$params[':startTime'] = $startTime;
			$params[':endTime'] = $endTime;
			$params[':status'] = $status;
			$params[':username'] = $_SESSION['email'];

			// Prepare and execute the query using PDO
			$stmt = $db->conn->prepare($query); // Use prepare instead of query
			$stmt->execute($params); // Pass the parameters

			// Check if any rows were returned
			if ($stmt->rowCount() > 0) { ?>
					<!-- Display the table as before -->
				<table style="width: 100%" class="table table-striped" id="letter">
					<tr>
						<th>#</th>
						<th>Customer</th>
						<th>Unit</th>
						<th>Service</th>
						<th>Price</th>
						<th>Qty</th>
						<th>Amount(&#x20A6)</th>
						<th>Status</th>
						<th>Date</th>
						<th>Time</th>
					</tr>
					<?php
						$i = 1;
						while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
						<tr>
							<td><?= $i++ ?></td>
							<td><?= $row['Customer'] ?></td>
							<td><?= $row['Department'] ?></td>
							<td><?= $row['Product'] ?></td>
							<td><?= $row['Price'] ?></td>
							<td><?= $row['qty'] ?></td>
							<td><?= $row['Amount'] ?></td>
							<td><?= $row['Status'] ?></td>
							<td><?= $row['TransacDate'] ?></td>
							<td><?= $row['TransacTime'] ?></td>
						</tr>
					<?php endwhile ?>
				</table>
			<?php
			} else {
					echo "No records found!";
			}
	}
	}

?>

<?php require 'partials/footer.php'; ?>
<script>
	$(document).ready(function(){
		$('#adminReport').on('submit', function(e){
			e.preventDefault();
			$.ajax({
				url: 'model/user.report.php',
				type: 'POST',
				dataType: 'JSON',
				data: $(this).serialize(),
				success: function(response){
					if(response.status){
						$('#errorUnit, #errorProduct, #errorF, #errorS, #errorC, #errorE').text('');
						$('#reportResults').html(response.tableData);
						//alert('success')
					}else{
						$('#errorUnit').text(response.errors.unit || '');
						$('#errorProduct').text(response.errors.product || '');
						$('#errorF').text(response.errors.startDate || '');
						$('#errorS').text(response.errors.endDate || '');
						$('#errorC').text(response.errors.currentTime || '');
						$('#errorE').text(response.errors.lasstime || '');

						if(response.errors.length > 0){
							$('#reportResults').html('<p>No records found.</p>');
						}
					}
				},
				error: function(xhr, status, error){
					alert('error' + status);
				}
			});
		});
	});
</script>