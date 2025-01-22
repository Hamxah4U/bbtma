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
		<div id="letter__">

				<!-- Topbar -->
				<?php
						require 'partials/nav.php';
				?>
				<!-- End of Topbar -->

				<!-- Begin Page Content -->
				<div class="container-fluid">

						<!-- Page Heading -->
						<!-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
								<h1 class="h3 mb-0 text-gray-800"></h1>
							<button class="btn btn-primary" type="button" data-target="#modalUser" data-toggle="modal">Add User</button>
						</div> -->

            <form id="classlist">
              <label for="">List of Class</label>
              <select name="class" id="selectclass" class="form-control">
                <option value="--choose--">--choose--</option>
                <?php
                  $sql = $db->conn->prepare('SELECT * FROM `class_tbl` WHERE `Status` = "Active" ');
                  $sql->execute();
                  $classes = $sql->fetchAll(PDO::FETCH_ASSOC);
                  foreach($classes as $class ):?>
                  <option value="<?= $class['class_ID'] ?>"><?= $class['Class_Name'] ?></option>
                <?php endforeach ?>
              </select>
              <br><br>
            </form>
						<!-- Content Row -->
						<form>
              <?php 
                $sqlid = $db->conn->prepare('SELECT * FROM `student_tbl`');  //  mysqli_query($conn, "SELECT * FROM `student_tbl`") or die();
                $sqlid->execute();// = mysqli_fetch_array($sqlid);
                $id = $sqlid->fetchAll(PDO::FETCH_ASSOC);
              ?>
            <div id="letter">
            <table class="table table-striped" id="usersTable">
							<thead>
								<tr>
									<th>#</th>
									<th>Fullname</th>
									<th>Admission</th>
									<th>Gender</th>
									<th>DOB</th>
									<th>Class</th>
									<th class="table-head">Check All <input type="checkbox" id="allcb" name="allcb"/></th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
            </div>
            <div class="form-group row">
              <!-- Print Button -->
              <div class="col-lg-4">
                <button class="btn btn-primary btn-lg" id="btn2" type="button" onclick="PrintDoc()">Print</button>
              </div>

              <div class="col-lg-4">
                <select name="class" id="classSelect" class="form-control">
                  <option value="--choose--">--choose--</option>
                  <?php
                    $sql = $db->conn->prepare('SELECT * FROM `class_tbl` WHERE `Status` = "Active" ');
                    $sql->execute();
                    $classes = $sql->fetchAll(PDO::FETCH_ASSOC);
                    foreach($classes as $class ):?>
                    <option value="<?= $class['class_ID'] ?>"><?= $class['Class_Name'] ?></option>
                  <?php endforeach ?>                 
                </select>
              </div>

              <div class="col-lg-4">
                <button class="btn btn-primary" name="promotedTo">Promoted To</button>
              </div>
            </div>

            </form>
						<!-- Content Row -->
				</div>
				<!-- /.container-fluid -->

		</div>
		<!-- End of Main Content -->

<?php
    require 'partials/footer.php';
?>
<script  type="text/javascript">
    $(document).ready(function() {
    $('#allcb').click(function(e) {
        $('[name="myID[]"]').prop('checked', this.checked);
    });

    $('[name="myID[]"]').click(function(e) {
        if ($('[name="myID[]"]:checked').length == $('[name="myID[]"]').length || !this.checked)
            $('#allcb').prop('checked', this.checked);
    });

});
</script>

<script>
$(document).ready(function () {
    $('#selectclass').on('change', function () {
        let selectedClass = $(this).val();
        if (selectedClass !== "--choose--") {
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
                                <td>${student.Reg_no}</td>
                                <td>${student.Gender}</td>
                                <td>${student.DOB}</td>
                                <td>${student.Class_Name}</td>
                                <td><center><input id="myID" type="checkbox" name="myID[]" value="${student.stu_ID}" style="width: 100%;"></center></td>
                            </tr>

                        `;
                        $('#usersTable tbody').append(row);
                    });
                },
                error: function () {
                    alert('Failed to fetch students. Please try again.');
                }
            });
        } else {
            alert('Please select a valid class.');
        }
    });
});
</script>


<script>
    function PrintDoc() { 
    var divToPrint = document.getElementById('letter');
    var htmlToPrint = '' +
        '<style type="text/css">' +
        /* '#customers {' +
        'font-family:algerian' +
        'width: 100%;' +
        '}' +
        '#customers td, #customers th {' +
        'border: 1px solid #ddd;' +
        'padding: 8px;' +
        '}' +
        '#customers tr:nth-child(even){' +
        'background-color: #f2f2f2;' +
        '}' + */
        '#tabletitle th {' +
        'border: 1px solid black;' +
        'cellpadding=0px;' +
        'font-size: 12pt;' +
        'background-color: #f2f2f2' +
        'color:red;' +
        'height:0px;' +
        'background-color: #f2f2f2' +
        '}' +
        '.special tr:nth-child(even){' +
        'background-color: #f2f2f2;' +
        'border: 1px solid black;' +
        '}' +
        '#mybody td {' +
        'border: 1px solid black;' +
        'cellpadding=0px;' +
        'font-size: 11pt;' +
        'color:black;' +
        'height:0px;' +
        '}' +
        'p{' +
        'margin-left: 50px' +
        '}' +
        '#mytablehead td{' +
        'color:black' +
        '}' +
        'table tr:nth-child(even){' +
        'background-color: #f2f2f2;' +
        '}' +
        '#customers tr:nth-child(even){' + 
        'background-color: #ddd;' +
        '}' +
        'body{' +
        'background-image: url(images/logo.png);'+
        '}'+
        '</style>';
    htmlToPrint += letter.outerHTML;
    newWin = window.open('fff', 'jjjjjjjj', 'left=300,top=100,width=1000,height=700,toolbar=0,scrollbars=0,status=0');
    newWin.document.write(htmlToPrint);
    newWin.document.write('<html><head><style type="text/css">');
    newWin.document.write('</style></head><body onload="window.print();">');
    //newWin.document.write("<br/>");
    //newWin.document.write(Date());
    //newWin.document.close();
    newWin.focus();
    newWin.print();
    newWin.close();
}
</script>


