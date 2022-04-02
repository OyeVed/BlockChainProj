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
$oldpassword = $_POST['oldpassword'];
$newpassword = $_POST['newpassword'];
$confnewpassword = $_POST['confnewpassword'];

//Logic for chnaging Password.
$sql = "SELECT name, email, password FROM user_tables WHERE email=:email and password=:oldpassword";
$query= $conn -> prepare($sql);
$query-> bindParam(':email', $email, PDO::PARAM_STR);
$query-> bindParam(':oldpassword', $oldpassword, PDO::PARAM_STR);
$query-> execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
if($query->rowCount() > 0){
    if($newpassword == $confnewpassword){
        $sql1 = "UPDATE user_tables SET password = '$newpassword' WHERE email = '$email'";
        $conn->exec($sql1);
        echo ".";
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@8'></script><script type='text/javascript'>console.log('Error: ' );
            Swal.fire
            ({
                'title': 'Successful',
                'text': 'Password Changed Successfully.',
                'type': 'success'
            })
            </script>";
            echo "<script>
            setTimeout(function() {
             var asd = location.hostname;
             var asd = 'http://'+asd+'/BlockChainProj/front-end/';
              window.location.href = asd;
            }, 2000);
            </script>";
    }
    else{
        echo ".";
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@8'></script><script type='text/javascript'>console.log('Error: ' );
        Swal.fire
        ({
            'title': 'Errors',
            'text': 'Your Password and Confirm Password does not match.',
            'type': 'error'
        })
        </script>";
        echo "<script>
        setTimeout(function() {
         var asd = location.hostname;
         var asd = 'http://'+asd+'/BlockChainProj/front-end/';
          window.location.href = asd;
        }, 2000);
        </script>";
        }
}
else{
    echo ".";
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@8'></script><script type='text/javascript'>console.log('Error: ' );
    Swal.fire
    ({
        'title': 'Errors',
        'text': 'Credentials that you entered are Wrong.',
        'type': 'error'
    })
    </script>";
    echo "<script>
    setTimeout(function() {
     var asd = location.hostname;
     var asd = 'http://'+asd+'/BlockChainProj/front-end/';
      window.location.href = asd;
    }, 2000);
    </script>";
}
?>