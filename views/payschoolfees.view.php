<?php
require './model/Database.php';
  require 'views/partials/security.php';
	require 'views/partials/header.php';

  if(isset($_GET['code'])){
    $code = $_GET['code'];
  }

  $stmt = $db->conn->prepare("SELECT * FROM `student_tbl` WHERE `student_tbl`.`stu_ID` = :id ");
  $stmt->execute([':id' => $code]);
  $student = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Page Wrapper -->
<div id="wrapper">
    <!-- Sidebar -->
    <?php require 'views/partials/sidebar.php' ?>


  <div id="content-wrapper" class="d-flex flex-column">

		<!-- Main Content -->
		<div id="content">

				<!-- Topbar -->
				<?php		require 'views/partials/nav.php'; ?>
				
				<div class="container-fluid">
        <form id="schfees">
          <input type="hidden" name="stdid" value="<?= $code ?>">

          <div class="form-row">
            <div class="col-md-5 mb-3">
                <input type="number" name="cr" placeholder="Amount e.g 12,500" class="form-control">
                <div class="text-danger" id="errorFirstname"></div>
                <small class="text-danger" id="cr"></small>               
            </div>
            <div class="col-md-5 mb-3">
              <input type="text" name="narr" placeholder="Narration" class="form-control">
              <small class="text-danger" id="narr"></small>
            </div>
            <div class="col-md-2 mb-3">
                <button type="submit" class="btn btn-primary form-control">Save</button>
            </div>                
          </div>

        <div class="d-sm-flex align-items-center justify-content-between mb-4">          
            <h5 class="h5 mb-0 text-gray-800">
              <strong>
                <div id="nonPrintable" class="panel-heading"><strong>Student's School Fees Report: &nbsp;</strong><strong style="float: right;color:MediumSeaGreen;font-size:12pt"><?=  $student['Reg_no'].'-'.$student['FirstName'].' '. $student['OtherName']. ' '. $student['Surname']  ?></strong></div>
            </strong>
          </h5>          
          </a>
        </div>
        
          <div class="dataTable_wrapper" style="overflow: auto">
            <table class="table table-striped table-bordered table-hover" id="dataTables-example" style="width:100%;color:dodgerblue; font: size 120pt;">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Schl Fees</th>
                  <th>Paid</th>
                  <th>Balance</th>
                  <th>Receipt</th>
                  <th>Narration</th>
                  <th>Serviceby</th>
                  <th>Date/Time</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $stmt = $db->conn->prepare('SELECT * FROM `schoolfees_tbl` WHERE `stdid` = :id ORDER BY `Time` Desc ');
                  $stmt->execute([
                    ':id' => $code
                  ]);
                  $fees = $stmt->fetchAll(PDO::FETCH_ASSOC);
                  foreach($fees as $index=>$fee):?>
                  <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= number_format($fee['fees'], 2) ?></td>
                    <td><?= number_format($fee['payments'], 2) ?></td>
                    <td><?php $bal = $fee['fees'] - $fee['payments'];//echo $bal.'.00'; ?></td>
                    <td>
                      <?php
                        if($fee['payments'] != '' && $fee['fees'] == 0):?>
                          <a rel="facebox" href="/receipt?referenceID=<?= $fee['id']; ?>" class="btn btn-sm btn-primary">Receipt</a>							<?php
                        endif;
                      ?>
                    </td>
                    <td><?= $fee['Narration'] ?></td>                    
                    <td><?= $fee['Cashby'] ?></td>
                    <td><?= $fee['created_at'] ?></td>
                  </tr>
                <?php endforeach ?>
                <tr>						
						      <th><strong>Total</strong></th>
                  <?php                     
                    $stmtDebit = $db->conn->prepare('SELECT SUM(fees) AS Total FROM schoolfees_tbl WHERE stdid = :id');
                    $stmtDebit->execute([':id' => $code]);
                    $rowDebit = $stmtDebit->fetch(PDO::FETCH_ASSOC);

                    $stmtCredit = $db->conn->prepare('SELECT SUM(payments) AS Total FROM schoolfees_tbl WHERE stdid = :id');
                    $stmtCredit->execute([':id' => $code]);
                    $rowCredit = $stmtCredit->fetch(PDO::FETCH_ASSOC);

                    $debitTotal = $rowDebit['Total'] ?? 0;
                    $creditTotal = $rowCredit['Total'] ?? 0;

                    $newbal = $creditTotal - $debitTotal
                  ?>
                    <th><strong class="text-warning"><?php echo number_format($rowDebit['Total']).'.00';?></strong></th>
                    <th><strong class="text-success"><?= number_format($creditTotal).'.00'; ?></strong></th>
                    <th><strong class="text-danger"><?= number_format($newbal, 2) ?></strong></th>
                    <th><strong></strong></th>
                    <th><strong></strong></th>
                    <th><strong></strong></th>
                    <th><strong></strong></th>
                  </tr>	
              </tbody>
            </table>
          </div>
        </form>

				<!-- /.container-fluid -->

		</div>
		<!-- End of Main Content -->

<?php
    require 'views/partials/footer.php';
?>

<script>
  $(document).ready(function(){
    $('#schfees').on('submit', function(e){
      e.preventDefault();
      $('.text-danger').text('');
      $.ajax({
        url: 'model/payschoolfees.php',
        dataType: 'JSON',
        data: $(this).serialize(),
        type: 'POST',
        success: function(response){
          if(! response.status){
            $.each(response.errors, function(key, value){
              $('#'+key).text(value);
            });
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
              title: "Student's wallet was founded"
            });
            $('#schfees')[0].reset();
            setTimeout(function(){
              location.reload();
            }, 3000);
          }
        },
        error: function(xhr, status, error){
          alert('error' + xhr + status + error)
        }
      })
    })
  });
</script>


<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
                responsive: true
        });
    });
</script>


