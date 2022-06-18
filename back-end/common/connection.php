<?php

$servername = 'testdb.cm6ahp8bvbm1.ap-south-1.rds.amazonaws.com:3306';
$username = 'admin';
$password = 'admin1234';
$dbname = "block_chain";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //echo "Connected successfully";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

?>