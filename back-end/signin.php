<?php
    session_start();
?>
<html>
<link rel="shortcut icon" type="image/x-icon" href="../front-end/images/android-icon-192x192.png">
<body style="background-color:#009688;">
  <body/>
  </html>
<?php
//Importing connection file and starting session.
require_once("common/connection.php");

//Taking variables form the HTML.
$email = $_POST['email'];
$password = $_POST['password'];

//Logic for Signin.
$sql = "SELECT name, email, password FROM user_tables WHERE email=:email and password=:password";
$query= $conn -> prepare($sql);
$query-> bindParam(':email', $email, PDO::PARAM_STR);
$query-> bindParam(':password', $password, PDO::PARAM_STR);
$query-> execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
if($query->rowCount() > 0)
{
    $_SESSION['email'] = $email;
    $myaddress = $_SERVER['SERVER_NAME'];
    $bulk = "http://".$myaddress."/BlockChainProj/front-end/dashboard.php";
    echo "<script>window.location.href = '$bulk'</script>";
    //echo "<script>window.location.href = 'http://game.oyesters.in/BlockChainProj/front-end/dashboard.php'</script>";
}
else
{
    echo ".";
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@8'></script><script type='text/javascript'>console.log('Error: ' );
    Swal.fire
    ({
        'title': 'Errors',
        'text': 'You have entered wrong credentials. Please try again',
        'type': 'error'
    })
    </script>";
}
echo "<script>
setTimeout(function() {
 var asd = location.hostname;
 var asd = 'http://'+asd+'/BlockChainProj/front-end/';
  window.location.href = asd;
}, 2000);
</script>";
$conn = null;
?>