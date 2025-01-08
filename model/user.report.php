<?php
    require 'Database.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $errors = [];
      $success = [];

      // Fetching form inputs
      $unit = $_POST['unit'];
      $product = $_POST['product'];
      $startDate  = $_POST['sdate'];
      $endDate = $_POST['edate'];
      $startTime  = $_POST['stime'];
      $endTime = $_POST['etime'];
      $status = $_POST['status'];
      $user = $_POST['user'];

      // Input validations
      if(empty(trim($unit))){
        $errors['unit'] = 'Department is required!';
      }

      if(empty(trim($product))){
        $errors['product'] = 'Product is required!';
      }

      if(empty(trim($startDate))){
        $errors['startDate'] = 'Starting date is required!';
      }

      if(empty(trim($endDate))){
        $errors['endDate'] = 'Ending date is required!';
      }

      if(empty(trim($startTime))){
        $errors['currentTime'] = 'Starting Time is required!';
      }

      if(empty(trim($endTime))){
        $errors['lasstime'] = 'End Time is required!';
      }

      // If no validation errors, proceed with the query
      if(empty($errors)){
        $query = "SELECT * FROM transaction_tbl t 
                JOIN product_tbl p ON t.Product = p.proID 
                JOIN department_tbl d ON t.tDepartment = d.deptID 
                WHERE 1=1";

        if ($unit != "all") {
            $query .= " AND d.Department LIKE '%" . $unit . "%'";
        }

        if ($product != "") {
            $query .= " AND p.Productname LIKE '%" . $product . "%'";
        }

        // Add additional conditions for date, time, and status
        $query .= " AND t.TransacDate BETWEEN '$startDate' AND '$endDate' 
                    AND t.TransacTime BETWEEN '$startTime' AND '$endTime' 
                    AND t.Status = '$status'";

        if ($user != "all") {
            $query .= " AND t.TrasacBy = '$user'";
        }

        $result = $db->conn->query($query);

        if (!$result) {
            die("Query execution failed: " . $conn->error);
        }

        // Check if we got any records
        if($result->rowCount() > 0) {
          // Start building the table HTML
          $tableData = '<table style="width: 100%" class="table table-striped" id="letter">';
          $tableData .= '<tr>
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
            </tr>';

          $i = 1;
          $totalamount = 0;

          // Fetch data and build table rows
          while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
              $tableData .= '<tr style="text-align: cent;">
                  <td>' . $i++ . '</td>
                  <td>' . $row['Customer'] . '</td>
                  <td>' . $row['Department'] . '</td>
                  <td>' . $row['Productname'] . '</td>
                  <td>' . number_format($row['Price']) . '</td>
                  <td>' . $row['qty'] . '</td>
                  <td>' . number_format($row['Amount']) . '</td>
                  <td>' . $row['Status'] . '</td>
                  <td>' . $row['TransacDate'] . '</td>
                  <td>' . $row['TransacTime'] . '</td>
                </tr>';
              $totalamount += $row['Amount'];
          }

          // Add the total amount row
          $tableData .= '<tr style="font-weight: bold;">
              <td>&nbsp;</td>
              <td style="text-align:right"><strong>Total Amount:</strong></td>
              <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
              <td>' . '&#x20A6 ' . number_format($totalamount, 2, '.', ',') . '</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>';

          $tableData .= '</table>';

          // Send the table data back in the JSON response
          echo json_encode([
              'status' => true,
              'tableData' => $tableData,
              'success' => $success,
          ]);
        } else {
          // No data found
          echo json_encode([
              'status' => false,
              'errors' => ['No records found.'],
          ]);
        }
      } else {
        // Validation errors found
        echo json_encode([
          'status' => false,
          'errors' => $errors,
        ]);
      }
    }
?>

<?php
    /* require 'Database.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $errors = [];
      $success = [];

      $unit = $_POST['unit'];
      $product = $_POST['product'];
      $startDate  = $_POST['sdate'];
      $endDate = $_POST['edate'];
      $startTime  = $_POST['stime'];
      $endTime = $_POST['etime'];
      $status = $_POST['status'];
      $user = $_POST['user']; 

      if(empty(trim($unit))){
        $errors['unit'] = 'Department is required!';
      }

      if(empty(trim($product))){
        $errors['product'] = 'Product is required!';
      }

      if(empty(trim($startDate))){
        $errors['startDate'] = 'Starting date is required!';
      }

      if(empty(trim($endDate))){
        $errors['endDate'] = 'Ending date is required!';
      }

      if(empty(trim($startTime))){
        $errors['currentTime'] = 'Starting Time is required!';
      }

      if(empty(trim($endTime))){
        $errors['lasstime'] = 'End Time is required!';
      }

      if(empty($errors)){
        // $query = "SELECT t.*, p.Productname, d.Department AS deptName, u.Fullname
        //   FROM transaction_tbl t 
        //   JOIN product_tbl p ON t.Product = p.proID 
        //   JOIN department_tbl d ON t.tDepartment = d.deptID
        //   JOIN users_tbl u ON u.userID = t.TrasacBy
        //   WHERE 1=1";
        $query = "SELECT * FROM transaction_tbl t 
                  JOIN product_tbl p ON t.Product = p.proID 
                  JOIN department_tbl d ON t.tDepartment = d.deptID 
                  WHERE 1=1"; 

        if ($unit != "all") {
            $query .= " AND d.Department LIKE '%" . $unit . "%'";
        }

        if ($product != "") {
            $query .= " AND p.Productname LIKE '%" . $product . "%'";
        }

        // Add additional conditions for date, time, and status
        $query .= " AND t.TransacDate BETWEEN '$startDate' AND '$endDate' 
                    AND t.TransacTime BETWEEN '$startTime' AND '$endTime' 
                    AND t.Status = '$status'";

        // User selection filter
        if ($user != "all") {
            $query .= " AND t.TrasacBy = '$user'";  // Ensure user ID is being filtered correctly
        }

        $result = $db->conn->query($query);

        if (!$result) {
            die("Query execution failed: " . $conn->error);
        }

        if($result->rowCount() > 0) {
          $tableData = '<table style="width: 100%" class="table table-striped" id="letter">';
          $tableData .= '<tr>
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
              <th>Transaction by</th>
            </tr>';

          $i = 1;
          $totalamount = 0;

          // Fetch data and build table rows
          while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
              $tableData .= '<tr style="text-align: cent;">
                  <td>' . $i++ . '</td>
                  <td>' . $row['Customer'] . '</td>
                  <td>' . $row['deptName'] . '</td>
                  <td>' . $row['Productname'] . '</td>
                  <td>' . number_format($row['Price']) . '</td>
                  <td>' . $row['qty'] . '</td>
                  <td>' . number_format($row['Amount']) . '</td>
                  <td>' . $row['Status'] . '</td>
                  <td>' . $row['TransacDate'] . '</td>
                  <td>' . $row['TransacTime'] . '</td>
                  <td>' . $row['Fullname'] . '</td>

                </tr>';
              $totalamount += $row['Amount'];
          }

          // Add the total amount row
          $tableData .= '<tr style="font-weight: bold;">
              <td>&nbsp;</td>
              <td style="text-align:right"><strong>Total Amount:</strong></td>
              <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
              <td>' . '&#x20A6 ' . number_format($totalamount, 2, '.', ',') . '</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>';

          $tableData .= '</table>';

          // Send the table data back in the JSON response
          echo json_encode([
              'status' => true,
              'tableData' => $tableData,
              'success' => $success,
          ]);
        } else {
          // No data found
          echo json_encode([
              'status' => false,
              'errors' => ['No records found.'],
          ]);
        }
      } else {
        // Validation errors found
        echo json_encode([
          'status' => false,
          'errors' => $errors,
        ]);
      }
    }  */
?>