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


  <div id="content-wrapper" class="d-flex flex-column">
		<!-- Main Content -->
		<div id="content">
      <!-- Topbar -->
      <?php		require 'views/partials/nav.php'; ?>				
      <div class="container-fluid">        
        <div id="letter">      
          <img src="../img/receipt.png" height="" style="width: 100%;">
          <table class="able" style="width:90%;" id="mtablehead">
		        <?php
              if(isset($_GET['referenceID'])){
                $id = $_GET['referenceID'];
              }
              $stmt = $db->conn->prepare("SELECT * FROM schoolfees_tbl JOIN `student_tbl` ON std_ID = `student_tbl`.`stu_ID` WHERE `schf_ID` = :id ");
              $stmt->execute([':id' => $id]);
              $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
              if(count($rows) > 0):
                foreach($rows as $row): ?>
                  <tr style="text-align: justify;">
						        <th>Payment ID:</th>
						        <td><?= $row['schf_ID'] ?></td>
					        </tr>

                  <tr style="text-align: justify;">
						        <th>Student</th>
						        <td><?= $row['FirstName'].' '.$row['Surname']	?></td>
					        </tr>

                  <tr style="text-align: justify;">
                    <th>Amount Paid:</th>
                    <td><?php echo '<span>&#8358; </span>'.$row['CreditSide'] ?></td>
                  </tr>
                  <tr style="text-align: justify;">
                    <th>Date:</th>
                    <td><?php echo $row['Date'] ?></td>
                  </tr>
                  <tr style="text-align: justify;">
                    <th>Time:</th>
                    <td><?php echo $row['Time'] ?></td>
                  </tr>
                  <tr style="text-align: justify;">
                    <th>Narration:</th>
                    <td><?php echo $row['Narration'] ?></td>
                  </tr>
                  <tr style="text-align: justify;">

                    <th>Receiver</th>
                    <td><?php echo $row['Cashby'] ?></td>
                  </tr>
              <?php endforeach ?>
              <?php endif ?>          
        </table>
        <p>Powered by: HID Tech Solutin</p>
    	  <p>+2348037856962</p> 
        <td><input id="btn2" class="btn btn-dark" type="button" value="Print" onclick="PrintDoc2()" /></td>

        </div>
      </div>
		<!-- End of Main Content -->

<?php require 'views/partials/footer.php'; ?>

<script>
  $(document).ready(function(){
    $('#schfees').on('submit', function(e){
      e.preventDefault();
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
            alert('yes');
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


<script>
  function PrintDoc2() {
    const content = document.getElementById('letter').innerHTML;
    const newWindow = window.open('', '_blank', 'left=300,top=100,width=1000,height=700,toolbar=0,scrollbars=0,status=0');

    newWindow.document.write(`
      <html>
      <head>
        <title>Print Preview</title>
        <style>
          /* Include any styles required for the content */
          body {
            font-family: Arial, sans-serif;
          }
          table {
            width: 100%;
            border-collapse: collapse;
          }
          th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
          }
          .footer {
            text-align: center;
            margin-top: 20px;
          }
        </style>
      </head>
        <body>
          ${content}
        </body>
        </html>
    `);

    newWindow.document.close();
    newWindow.onload = function () {
      newWindow.print();
    };
  }
</script>

