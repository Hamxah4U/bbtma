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
        <div class="dataTable_wrapper" style="overflow: auto">
          <table class="table table-striped table-bordered table-hover" id="dataTables-example" style="width:100%">
            <thead>
              <tr>
                <th>#</th>
						    <th>Fullname</th>
						    <th>Email</th>
                <th>Phone</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            <?php
              $stmt = $db->conn->prepare('SELECT * FROM `users_tbl` WHERE `Email` != "hamxah4u@gmail.com" ');          
              $stmt->execute();
              $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
              $i = 1;
              foreach($users as $user):?>
                <tr>
                  <td><?= $i++ ?></td>
                  <td><?= $user['Fullname'] ?></td>
                  <td><?= $user['Email'] ?></td>
                  <td><?= $user['Phone'] ?></td>
                  <td>
                    <a href="/assigsub?id=<?= $user['user_ID']; ?>" title="Edit Student Information"><p class="icofont-edit" style="float: left;"></p></a>
                  </td>
                </tr>
              <?php endforeach ?>
                </tbody>
            </table>
            </div> 
				</div>
				<!-- /.container-fluid -->

		</div>
		<!-- End of Main Content -->

<?php
    require 'views/partials/footer.php';
?>

<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
                responsive: true
        });
    });
</script>


