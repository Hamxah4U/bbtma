<?php
require 'partials/security.php';
require 'partials/header.php';
require 'model/Database.php';
?>
    <!-- Page Wrapper -->
<div id="wrapper">    
  <?php require 'partials/sidebar.php' ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
          <!-- Topbar -->
          <?php require 'partials/nav.php'; ?>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">
              <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <?php if($_SESSION['role'] == '1'):?>
                  <h5 class="h5 mb-0 text-gray-800"><strong>Admin Dashboard</strong></h5>
                <?php else: ?>
                  <h5 class="h5 mb-0 text-gray-800"><strong>User Dashboard</strong></h5>  
                <?php endif ?>
                <a href="/report" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                  class="fas fa-download fa-sm text-white-50"></i> <strong>Generate Report</strong>
                </a>
              </div>

              <?php if($_SESSION['role'] == '1'): ?>
                <strong>Staff Summary</strong>
                <div class="row">
                  <!-- Earnings (Monthly) Card Example -->
                  <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                      <div class="card-body">
                        <div class="row no-gutters align-items-center">
                          <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Male Staff</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php
                              $stmtMale = $db->query("SELECT * FROM `users_tbl` WHERE Gender = '1' AND `Status` = 'Active' ");
                              $male = $stmtMale->rowCount();
                              echo $male;                              
                            ?>
                            </div>
                          </div>
                          <div class="col-auto">
                          <i class="icofont-teacher fa-2x text-gray-300"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Female Staff</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php
                                            $stmtfMale = $db->query("SELECT * FROM `users_tbl` WHERE Gender = '2' AND `Status` = 'Active' ");
                                            $fmale = $stmtfMale->rowCount();
                                            echo $fmale;
                                        ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="icofont-girl-alt fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Staff</div>
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-auto">
                                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                <?php
                                                  $totalstd = $db->query("SELECT * FROM `users_tbl` WHERE`Status` = 'Active'");
                                                  $total = $totalstd->rowCount();
                                                  echo $total;
                                                ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="icofont-users-social fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><br/>

                <strong>Student Summary</strong>
                <div class="row">
                  <!-- Earnings (Monthly) Card Example -->
                  <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                      <div class="card-body">
                        <div class="row no-gutters align-items-center">
                          <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Male Student</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php
                              $stmtMale = $db->query("SELECT * FROM `student_tbl` WHERE Gender = '1' AND `Status` = 'Active' ");
                              $male = $stmtMale->rowCount();
                              echo $male;                              
                            ?>
                            </div>
                          </div>
                          <div class="col-auto">
                          <i class="icofont-student-alt fa-2x text-gray-300"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Female Student</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php
                                            $stmtfMale = $db->query("SELECT * FROM `student_tbl` WHERE Gender = '2' AND `Status` = 'Active' ");
                                            $fmale = $stmtfMale->rowCount();
                                            echo $fmale;
                                        ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="icofont-student fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Student</div>
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-auto">
                                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                <?php
                                                  $totalstd = $db->query("SELECT * FROM `student_tbl` WHERE`Status` = 'Active'");
                                                  $total = $totalstd->rowCount();
                                                  echo $total;
                                                ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="icofont-group-students fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

               
              <?php endif ?>

            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->
<?php
  require 'partials/footer.php';
?>


