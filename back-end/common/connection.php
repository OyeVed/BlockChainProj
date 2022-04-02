<?php
$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "block_chain";

$servername = 'database-1.cd9ccwppzsm2.ap-south-1.rds.amazonaws.com';
$username = 'admin';
$password = 'admin1234';

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //echo "Connected successfully";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

?>