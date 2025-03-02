<?php
    class Database{
      public $conn;
      public function __construct()
      {
        $dsn = 'mysql:host=localhost;dbname=schooldb'; //sfgeorgn_bbtma  schooldb
          $dbuser = 'root'; //sfgeorgn_bbtma
          $dbpass = ''; //*H@mxah4u#
          try {
            $this->conn = new PDO($dsn, $dbuser, $dbpass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo 'connected';
          } catch (PDOException $e) {
            die("DB Connection failed:" .$e->getMessage());
          }         
      }

      public function query($sql){
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt;
      }

      public function checkExist($sql, $param = []){
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($param);
        return $stmt;
      }
    }

    $db = new Database();
?>