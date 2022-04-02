<?php

$myaddress = $_SERVER['SERVER_NAME'];
$bulk = "http://".$myaddress.":3000/upload/home";

echo "<script>window.location.href = '$bulk'</script>";