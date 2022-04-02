<?php
    session_start();
    $user= $_SESSION['email'] ;
    if(!isset($_SESSION['email'])) {header('location:index.html');}
?>
<html>
<link rel="shortcut icon" type="image/x-icon" href="../front-end/images/android-icon-192x192.png">
<body style="background-color:#009688;">
  <body/>
  </html>
<?php
//Importing connection file and starting session.
require_once("common/connection.php");

//Setting the date and time.
date_default_timezone_set("Asia/Kolkata");
$date = date('yy-m-d');
$time =  date("h:i:sa");
$created_at = $date." ".$time;

//Taking variables form the HTML.
$name = $_POST['name'];
$phonenumber = $_POST['phonenumber'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirmpassword = $_POST['confirmpassword'];
$user_type = $_POST['user_type'];

//Check for Admin user
$stmt = $conn->prepare("SELECT * FROM user_tables WHERE email = '$user'");
$stmt->execute();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
foreach((new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
    if("$v[user_type]" == 1){
            
            //Logic for Signup
            if($password == $confirmpassword){
                $sql1 ="SELECT email FROM  user_tables WHERE email=:email";
                $query= $conn -> prepare($sql1);
                $query-> bindParam(':email', $email, PDO::PARAM_STR);
                $query-> execute();
                $results = $query -> fetchAll(PDO::FETCH_OBJ);
                if($query -> rowCount() > 0)
                {
                    echo ".";
                    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@8'></script><script type='text/javascript'>console.log('Error: ' );
                    Swal.fire
                    ({
                        'title': 'Errors',
                        'text': 'This email is already Registered',
                        'type': 'error'
                    })
                    </script>";
                    echo "<script>
                    setTimeout(function() {
                     var asd = location.hostname;
                     var asd = 'http://'+asd+'/BlockChainProj/front-end/view_user.php';
                      window.location.href = asd;
                    }, 2000);
                    </script>"; 
                }
                else{  
                    try{
                        $sql2 = "INSERT INTO user_tables (name, phonenumber, email, password, user_type) VALUES ('$name', '$phonenumber', '$email', '$password', '$user_type')";
                        // use exec() because no results are returned
                        $conn->exec($sql2);
                        echo ".";
                        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@8'></script><script type='text/javascript'>console.log('Error: ' );
                        Swal.fire
                        ({
                            'title': 'Successful',
                            'text': 'New User added Successfully.',
                            'type': 'success'
                        })
                        </script>";
                        echo "<script>
                        setTimeout(function() {
                         var asd = location.hostname;
                         var asd = 'http://'+asd+'/BlockChainProj/front-end/view_user.php';
                          window.location.href = asd;
                        }, 2000);
                        </script>";
                    } 
                    catch(PDOException $e) {
                        echo $sql2 . "<br>" . $e->getMessage();
                    }
                }
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
                 var asd = 'http://'+asd+'/BlockChainProj/front-end/private_signup.php';
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
            'text': 'You are not Atuthorised to Add user.',
            'type': 'error'
        })
        </script>";
        echo "<script>
        setTimeout(function() {
         var asd = location.hostname;
         var asd = 'http://'+asd+'/BlockChainProj/front-end/view_user.php';
          window.location.href = asd;
        }, 2000);
        </script>";
    }
}



$conn = null;

?>