<?php
    session_start();
    $user= $_SESSION['email'] ;
    if(!isset($_SESSION['email'])) {header('location:index.html');}
?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="shortcut icon" type="image/x-icon" href="images/android-icon-192x192.png">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Sign Up</title>
  </head>
  <body>
    <section class="material-half-bg">
      <div class="cover"></div>
    </section>
    <section class="signin-content">
      <div class="logo">
        <h1>Technology Academy</h1>
      </div>
      <div class="login-box">
        <form class="login-form" action="../back-end/signup.php" method="POST">
          <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>SIGN UP</h3>
          <div class="form-group">
            <label class="control-label">FULLNAME</label>
            <input class="form-control" type="text" name="name" placeholder="Full Name" autofocus required>
          </div>
          <div class="form-group">
            <label class="control-label">CONTACT NUMBER</label>
            <input class="form-control" type="number" name="phonenumber" placeholder="Phone Number" required>
          </div>
          <div class="form-group">
            <label class="control-label">EMAIL</label>
            <input class="form-control" type="email" name="email" placeholder="Email" required>
          </div>
          <div class="form-group">
            <label class="control-label">PASSWORD</label>
            <input class="form-control" type="password" name="password" placeholder="Password" required>
          </div>
          <div class="form-group">
            <label class="control-label">CONFIRM PASSWORD</label>
            <input class="form-control" type="password" name="confirmpassword" placeholder="Confirm Password" required>
          </div>
          <div class="form-group">
            <label class="control-label">USER TYPE</label>
            <input class="form-control" type="number" name="user_type" placeholder="User Type" required>
          </div>
          <div class="form-group btn-container">
            <button class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>SIGN UP</button>
          </div>
      </div>
    </section>
    <!-- Essential javascripts for application to work-->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="js/plugins/pace.min.js"></script>
    <script type="text/javascript">
      // Login Page Flipbox control
      $('.login-content [data-toggle="flip"]').click(function() {
      	$('.login-box').toggleClass('flipped');
      	return false;
      });
    </script>
  </body>
</html>