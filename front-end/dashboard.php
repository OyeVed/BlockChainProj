<?php
    session_start();
    $user= $_SESSION['email'] ;
    if(!isset($_SESSION['email'])) {header('location:index.html');}
	include('../back-end/common/connection.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <link rel="shortcut icon" type="image/x-icon" href="images/android-icon-192x192.png">
    <title>Blockchain Based Certificate Verification</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>
  <body class="app sidebar-mini">
    <!-- Navbar-->
    <header class="app-header"><!--<a class="app-header__logo"></a>-->
      <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
      <!-- Navbar Right Menu-->
      <ul class="app-nav">
        <h6 class= "app-header__logo1">Technology Academy</h6>
        <!-- User Menu-->
        <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>
          <ul class="dropdown-menu settings-menu dropdown-menu-right">
            <li><a class="dropdown-item" href="logout.php"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
          </ul>
        </li>
      </ul>
    </header>
    <!-- Sidebar menu-->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">
      <div class="app-sidebar__user"><img src="images/Final.jpeg" alt="User Image" height="100px" style="padding-left:60px;">
      </div>
      <?php
      $stmt = $conn->prepare("SELECT * FROM user_tables WHERE email = '$user'");
      $stmt->execute();
      $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
      foreach((new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
        $name = $v["name"];
      }
      ?>
      <div><p class="app-menu__label" style="text-align:center; color:white; font-size:larger;"><?php echo "Hello !!<br>";?> <?php echo "$name";?></p></div>
      <ul class="app-menu">
        <li><a class="app-menu__item active" href="dashboard.php"><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Dashboard</span></a></li>
        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-edit"></i><span class="app-menu__label">Issue Certificates</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
            <li><a class="treeview-item" href="single_certificate.php"><i class="icon fa fa-circle-o"></i>Single Certificate</a></li>
            <li><a class="treeview-item" href="bulk_certificate.php"><i class="icon fa fa-circle-o"></i>Bulk Certificates</a></li>
          </ul>
        </li>
        <li><a class="app-menu__item" href="view_certificate.php"><i class="app-menu__icon fa fa-cubes"></i><span class="app-menu__label">View Certificates</span></a></li>
        <li><a class="app-menu__item" href="view_user.php"><i class="app-menu__icon fa fa-id-card"></i><span class="app-menu__label">View Users</span></a></li>
        <li><a class="app-menu__item" href="view_courses.php"><i class="app-menu__icon fa fa-graduation-cap"></i><span class="app-menu__label">View Courses</span></a></li>
    </aside>
    <?php
    date_default_timezone_set("Asia/Kolkata");
    $date = date('Y-m-d');
    $stmt= $conn->prepare("SELECT COUNT(*) as count FROM certificates");
    $stmt->execute();
    $result=$stmt->setFetchMode(PDO::FETCH_ASSOC);
    foreach((new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
        $tcertificate = $v["count"];
	}
	$stmt1= $conn->prepare("SELECT COUNT(*) as count1 FROM certificates WHERE SUBSTRING(createdAt, 1, 10) = '$date'");
    $stmt1->execute();
    $result1=$stmt1->setFetchMode(PDO::FETCH_ASSOC);
    foreach((new RecursiveArrayIterator($stmt1->fetchAll())) as $k1=>$v1) {
         $ttoday = $v1["count1"];
	}
    ?>
    
    <main class="app-content">
      <div class="row">
        <div class="col-md-6 col-lg-4">
          <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
            <div class="info">
              <h4><a href="view_certificate.php">Total Certificates</a></h4>
              <p><b><?php echo"$tcertificate"; ?></b></p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="widget-small info coloured-icon"><i class="icon fa fa-thumbs-o-up fa-3x"></i>
            <div class="info">
              <h4><a href="issued_today.php">Issued Today</a></h4>
              <p><b><?php echo"$ttoday"; ?></b></p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="widget-small danger coloured-icon"><i class="icon fa fa-star fa-3x"></i>
            <div class="info">
              <h4>Total Emails Send</h4>
              <p><b><?php echo"$tcertificate"; ?></b></p>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8">
          <div class="tile">
            <h3 class="tile-title">System Summary</h3>
            <div class="text-lg-right">
            <div class="btn-group btn-group-sm">
              <form method="POST">
                <input type="submit" class="btn btn-info bg-white text-info" name="All" value="All" />
                <input type="submit" class="btn btn-info bg-white text-info" name="1m" value="1m" />
                <input type="submit" class="btn btn-info bg-white text-info" name="7d" value="7d" />
              </form>
              <?php
              	$dataPoints1=[];
                $dataPoints2=[];
                $dataPoints3=[];
                $dataPoints4=[];
                $count=0;
    			$stmt= $conn->prepare("SELECT * FROM email_tables");
    			$stmt->execute();
    			$result=$stmt->setFetchMode(PDO::FETCH_ASSOC);
    			foreach((new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
    				$dataPoints1[$count]=$v['send_date'];
        			$dataPoints2[$count]=$v['send_count'];
        			$dataPoints3[$count]=$v['view_count'];
        			$dataPoints4[$count]=$v['verify_count'];
        			$count++;
				}
				if(isset($_REQUEST['All']))
				{
    				$count=0;
    				$stmt= $conn->prepare("SELECT * FROM email_tables");
    				$stmt->execute();
    				$result=$stmt->setFetchMode(PDO::FETCH_ASSOC);
    				foreach((new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
    					$dataPoints1[$count]=$v['send_date'];
        				$dataPoints2[$count]=$v['send_count'];
        				$dataPoints3[$count]=$v['view_count'];
        				$dataPoints4[$count]=$v['verify_count'];
        				$count++;
					}
 				}
 				if(isset($_REQUEST['1m']))
				{
    				$count=0;
    				$stmt= $conn->prepare("SELECT * FROM email_tables WHERE (MONTH(send_date)=MONTH(CURRENT_DATE()) AND YEAR(send_date)=YEAR(CURRENT_DATE()))");
    				$stmt->execute();
    				$result=$stmt->setFetchMode(PDO::FETCH_ASSOC);
    				foreach((new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
    					$dataPoints1[$count]=$v['send_date'];
        				$dataPoints2[$count]=$v['send_count'];
        				$dataPoints3[$count]=$v['view_count'];
        				$dataPoints4[$count]=$v['verify_count'];
        				$count++;
					}
				}
				if(isset($_REQUEST['7d']))
				{
    				$count=0;
    				$stmt= $conn->prepare("SELECT * FROM email_tables WHERE send_date>now()-INTERVAL 7 day");
    				$stmt->execute();
    				$result=$stmt->setFetchMode(PDO::FETCH_ASSOC);
    				foreach((new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
    					$dataPoints1[$count]=$v['send_date'];
        				$dataPoints2[$count]=$v['send_count'];
        				$dataPoints3[$count]=$v['view_count'];
        				$dataPoints4[$count]=$v['verify_count'];
        				$count++;
					}
				}
              ?>
          </div>
      </div>
            <div class="embed-responsive embed-responsive-16by9">
              <canvas class="embed-responsive-item" id="lineChartDemo"></canvas>
            </div>
          </div>
        </div>
      </div>
    </main>
    <!-- Essential javascripts for application to work-->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="js/plugins/pace.min.js"></script>
    <!-- Page specific javascripts-->
    <script type="text/javascript" src="js/plugins/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <script type="text/javascript">
      new Chart(document.getElementById("lineChartDemo"), {
        type: 'line',
        data: {
            labels:<?php echo json_encode($dataPoints1,JSON_NUMERIC_CHECK); ?>,
            datasets: [{ 
                data:<?php echo json_encode($dataPoints2,JSON_NUMERIC_CHECK); ?>,
                label: "Sent",
                borderColor: "#ff2323",//red
                fill: false
            }, 
            { 
                data: <?php echo json_encode($dataPoints3,JSON_NUMERIC_CHECK); ?>,
                label: "Viewed",
                borderColor: "#0606c4",//blue
                fill: false
            }, 
            { 
                data:<?php echo json_encode($dataPoints4,JSON_NUMERIC_CHECK); ?>,
                label: "Verified",
                borderColor: "#059b19",//green
                fill: false
            }
            ]
        },
  options: {
    title: {
      display: true
    }
  }
});
    </script>
  </body>
</html>
