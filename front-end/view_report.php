<?php
session_start();
$user = $_SESSION['email'];
if (!isset($_SESSION['email'])) {
    header('location:index.html');
}
include('../back-end/common/connection.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="shortcut icon" type="image/x-icon" href="images/android-icon-192x192.png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
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
  <header class="app-header">
    <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar"
      aria-label="Hide Sidebar"></a>
    <!-- Navbar Right Menu-->
    <ul class="app-nav">
      <h6 class="app-header__logo1">Technology Academy</h6>
      <!-- User Menu-->
      <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i
            class="fa fa-user fa-lg"></i></a>
        <ul class="dropdown-menu settings-menu dropdown-menu-right">
          <li><a class="dropdown-item" href="logout.php"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
        </ul>
      </li>
    </ul>
  </header>
 <!-- Sidebar menu-->
 <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
  <aside class="app-sidebar">
    <div class="app-sidebar__user"><img src="images/Final.jpeg" alt="User Image" height="100px"
        style="padding-left:60px;">
    </div>
    <?php
      $stmt = $conn->prepare("SELECT * FROM user_tables WHERE email = '$user'");
      $stmt->execute();
      $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
      foreach((new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
        $name = $v["name"];
      }
      ?>
    <div>
      <p class="app-menu__label" style="padding-left:40px; color:white; font-size:larger;">
        <?php echo "Hello, "."$name";?>
      </p>
    </div>
    <ul class="app-menu">
      <li><a class="app-menu__item" href="dashboard.php"><i class="app-menu__icon fa fa-dashboard"></i><span
            class="app-menu__label">Dashboard</span></a></li>
      <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i
            class="app-menu__icon fa fa-edit"></i><span class="app-menu__label">Issue Certificates</span><i
            class="treeview-indicator fa fa-angle-right"></i></a>
        <ul class="treeview-menu">
          <li><a class="treeview-item" href="single_certificate.php"><i class="icon fa fa-circle-o"></i>Single
              Certificate</a></li>
          <li><a class="treeview-item" href="bulk_certificate.php"><i class="icon fa fa-circle-o"></i>Bulk
              Certificates</a></li>
        </ul>
      </li>
      <li><a class="app-menu__item" href="view_certificate.php"><i class="app-menu__icon fa fa-cubes"></i><span
            class="app-menu__label">View Certificates</span></a></li>
      <li><a class="app-menu__item" href="view_user.php"><i class="app-menu__icon fa fa-id-card"></i><span
            class="app-menu__label">View Users</span></a></li>
      <li><a class="app-menu__item active" href="view_courses.php"><i
            class="app-menu__icon fa fa-graduation-cap"></i><span class="app-menu__label">View Courses</span></a></li>
  </aside>
  <main class="app-content">
      <?php

        if(!isset($_GET['courseid'])){
          ?>
    <script>
      window.location.href = 'view_courses.php';
    </script>
    <?php
        } else {

          $course_id = $_GET['courseid'];
          
          $stmt = $conn->prepare("
          SELECT
          course_table.course_id AS 'id',
          course_table.course_name AS 'name',
          course_table.course_student_count AS 'no_of_students',
          90 AS 'avg_attendance',
          AVG(student_table.student_pre_assesment_score) AS 'avg_pre_assessment',
          AVG(student_table.student_post_assesment_score) AS 'avg_post_assessment',
          course_table.course_trainer AS 'trainer'
          FROM course_table
          JOIN student_table ON student_table.student_course_id = course_table.course_id
          WHERE course_table.course_id = $course_id
          GROUP BY student_table.student_course_id;
          ");
          $stmt->execute();
          $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

          $course = array();
          
          foreach((new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
            foreach ($v as $key => $value) {
              array_push($course, $value);
            }
          }
          if(count($course) == 0){
            ?>
    <script>
      window.location.href = 'view_courses.php';
    </script>
    <?php
          }
        }
      ?>
  <div class="app-title">
      <div style="width: 100%;">
        <h1 style="display: inline;">Report :-</h1>
        <span style="float: right;">
        <button class="btn btn-outline-success">Download Report</button>
        </span>
      </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile"  >
                <div class="tile-body">
                    <div class="mb-1" >
                        <h1><?php echo $course[1]; ?></h1>
                        <p>Total Students Enrolled: 3<br>Trainer Name: <?php echo $course[6]; ?><br>Duration:</p>
                    </div>
                </div>
            </div>
            <div class="row"  >
                <div class="col-8"  >
                <div class="tile"  >
            <h6 class="tile-title">Attendance :-</h6>
            <div style="height: 300px;">

                <canvas id="attendanceChart"></canvas>           
            </div>
          </div>
                </div>
                <div class="col-4"  >
                    <div class="tile"  >
                    <h6 class="tile-title">Assessment :-</h6>
                    <div style="height: 300px;" >
                        <canvas id="assesmentChart" ></canvas>   
                    </div>
                    </div>
                </div>
            </div>
            <div class="tile" >
            <h6 class="tile-title">Students :-</h6>
                <table class="table table-hover table-bordered" id="sampleTable" >
                    <thead>
                    <tr>
                      <th>Name</th>
                      <th>Pre-Assessment</th>
                      <th>Post-Assessment</th>
                      <th>Attendance</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                        $stmt = $conn->prepare("
                        SELECT
                        student_table.student_name AS 'name',
                        student_table.student_pre_assesment_score AS 'pre_assesment',
                        student_table.student_post_assesment_score AS 'post_assesment',
                        (SELECT COUNT(attendance_table.attendance_status) FROM attendance_table WHERE attendance_table.attendance_student_id=student_table.student_id AND attendance_table.attendance_status='P') 'final_attendance'
                        FROM student_table
                        WHERE student_table.student_course_id = $course_id
                        ");
                        $stmt->execute();
                        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
              
                        foreach((new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
                          ?>
                            <tr>
                                <td><?php echo $v['name']; ?></td>
                                <td><?php echo $v['pre_assesment']; ?></td>
                                <td><?php echo $v['post_assesment']; ?></td>
                                <td><?php echo $v['final_attendance']; ?></td>
                            <tr>
                          <?php
                        }
                    ?>
                    </tbody>
                </table>
            </div>
            <div class="tile " >
                <h4>Feedback :-</h4>
                <div class="row mt-3">
                <div class="col-6 mb-4"   >
                    <div class="d-flex justify-content-between " >
                        <div style="flex-basis: 55%;" >
                            <p>The trainer communicated clearly and was easy to understand</p>
                                <table style="width: 200px;" >
                                    <tr >
                                        <td class="pb-2" > <i class="fa fa-circle" aria-hidden="true" style="color:#16a5f2;" ></i>&nbsp;&nbsp;Excellent</td>
                                        <td class="pb-2">11</td>
                                    </tr>
                                    <tr>
                                        <td class="pb-2"><i class="fa fa-circle" aria-hidden="true" style="color:#f2bf16;" ></i>&nbsp;&nbsp;Good</td>
                                        <td class="pb-2">1</td>
                                    </tr>
                                    <tr>
                                        <td class="pb-2"><i class="fa fa-circle" aria-hidden="true" style="color:green;" ></i>&nbsp;&nbsp;Average</td>
                                        <td class="pb-2">2</td>
                                    </tr>
                                    <tr>
                                        <td class="pb-2"><i class="fa fa-circle" aria-hidden="true" style="color:#ab0c0c;" ></i>&nbsp;&nbsp;Poor</td>
                                        <td class="pb-2">0</td>
                                    </tr>
                                </table>
                        </div>
                        <div style="flex-basis: 40%;height:250px;" >
                            <canvas id="feedbackchart1" ></canvas> 
                        </div>
                    </div>
                </div>
                <div class="col-6 mb-4"   >
                    <div class="d-flex justify-content-between " >
                        <div style="flex-basis: 55%;" >
                            <p>The trainer communicated clearly and was easy to understand</p>
                                <table style="width: 200px;" >
                                    <tr >
                                        <td class="pb-2" > <i class="fa fa-circle" aria-hidden="true" style="color:#16a5f2;" ></i>&nbsp;&nbsp;Excellent</td>
                                        <td class="pb-2">11</td>
                                    </tr>
                                    <tr>
                                        <td class="pb-2"><i class="fa fa-circle" aria-hidden="true" style="color:#f2bf16;" ></i>&nbsp;&nbsp;Good</td>
                                        <td class="pb-2">1</td>
                                    </tr>
                                    <tr>
                                        <td class="pb-2"><i class="fa fa-circle" aria-hidden="true" style="color:green;" ></i>&nbsp;&nbsp;Average</td>
                                        <td class="pb-2">2</td>
                                    </tr>
                                    <tr>
                                        <td class="pb-2"><i class="fa fa-circle" aria-hidden="true" style="color:#ab0c0c;" ></i>&nbsp;&nbsp;Poor</td>
                                        <td class="pb-2">0</td>
                                    </tr>
                                </table>
                        </div>
                        <div style="flex-basis: 40%;height:250px;" >
                            <canvas id="feedbackchart2" ></canvas> 
                        </div>
                    </div>
                </div>
                <div class="col-6 mb-4"   >
                    <div class="d-flex justify-content-between " >
                        <div style="flex-basis: 55%;" >
                            <p>The trainer communicated clearly and was easy to understand</p>
                                <table style="width: 200px;" >
                                    <tr >
                                        <td class="pb-2" > <i class="fa fa-circle" aria-hidden="true" style="color:#16a5f2;" ></i>&nbsp;&nbsp;Excellent</td>
                                        <td class="pb-2">11</td>
                                    </tr>
                                    <tr>
                                        <td class="pb-2"><i class="fa fa-circle" aria-hidden="true" style="color:#f2bf16;" ></i>&nbsp;&nbsp;Good</td>
                                        <td class="pb-2">1</td>
                                    </tr>
                                    <tr>
                                        <td class="pb-2"><i class="fa fa-circle" aria-hidden="true" style="color:green;" ></i>&nbsp;&nbsp;Average</td>
                                        <td class="pb-2">2</td>
                                    </tr>
                                    <tr>
                                        <td class="pb-2"><i class="fa fa-circle" aria-hidden="true" style="color:#ab0c0c;" ></i>&nbsp;&nbsp;Poor</td>
                                        <td class="pb-2">0</td>
                                    </tr>
                                </table>
                        </div>
                        <div style="flex-basis: 40%;height:250px;" >
                            <canvas id="feedbackchart3" ></canvas> 
                        </div>
                    </div>
                </div>
                <div class="col-6 mb-4"   >
                    <div class="d-flex justify-content-between " >
                        <div style="flex-basis: 55%;" >
                            <p>The trainer communicated clearly and was easy to understand</p>
                                <table style="width: 200px;" >
                                    <tr >
                                        <td class="pb-2" > <i class="fa fa-circle" aria-hidden="true" style="color:#16a5f2;" ></i>&nbsp;&nbsp;Excellent</td>
                                        <td class="pb-2">11</td>
                                    </tr>
                                    <tr>
                                        <td class="pb-2"><i class="fa fa-circle" aria-hidden="true" style="color:#f2bf16;" ></i>&nbsp;&nbsp;Good</td>
                                        <td class="pb-2">1</td>
                                    </tr>
                                    <tr>
                                        <td class="pb-2"><i class="fa fa-circle" aria-hidden="true" style="color:green;" ></i>&nbsp;&nbsp;Average</td>
                                        <td class="pb-2">2</td>
                                    </tr>
                                    <tr>
                                        <td class="pb-2"><i class="fa fa-circle" aria-hidden="true" style="color:#ab0c0c;" ></i>&nbsp;&nbsp;Poor</td>
                                        <td class="pb-2">0</td>
                                    </tr>
                                </table>
                        </div>
                        <div style="flex-basis: 40%;height:250px;" >
                            <canvas id="feedbackchart4" ></canvas> 
                        </div>
                    </div>
                </div>
                <div class="col-12 mb-4" >
                    <p> Any other comments to Appreciate or help us Improve the training/workshop:</p>
                    <div class="d-flex justify-content-between" >
                        <div style="flex-basis: 25%;" class="d-flex justify-content-center align-items-center flex-column ">
                            <h1>7</h1>
                            <h6>Responses</h6>
                        </div>
                        <div style="flex-basis: 72%;" >
                            <p>"excellent as always. keep sharing the knowledge that will be of
great help for our future endeavor."</p>
                            <p>"Thank Mr. Abul Bayan"</p>
                            <p>"excellent as always. keep sharing the knowledge that will be of
great help for our future endeavor."</p>
                            <p>"Thank Mr. Abul Bayan"</p>
                            <p>"excellent as always. keep sharing the knowledge that will be of
great help for our future endeavor."</p>
                            <p>"Thank Mr. Abul Bayan"</p>
                            <p>"excellent as always. keep sharing the knowledge that will be of
great help for our future endeavor."</p>
                    </div>
                    </div>
                </div>
                <div class="col-12 mb-4" >
                    <p> Any other comments to Appreciate or help us Improve the training/workshop:</p>
                    <div class="d-flex justify-content-between" style="height: 150px;" >
                        <div style="flex-basis: 25%;" class="d-flex justify-content-center align-items-center flex-column ">
                            <h1>7</h1>
                            <h6>Responses</h6>
                        </div>
                        <div style="flex-basis: 72%;" class="d-flex justify-content-center align-items-center flex-column " >
                           <div class="d-flex" >
                           <i class="fa fa-star" aria-hidden="true"></i>
                           <i class="fa fa-star" aria-hidden="true"></i>
                           <i class="fa fa-star" aria-hidden="true"></i>
                           <i class="fa fa-star" aria-hidden="true"></i>
                           <i class="fa fa-star" aria-hidden="true"></i>
                           </div>
                            <p>9.21 Average Rating</p>
                    </div>
                    </div>
                </div>
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
    <!-- <script type="text/javascript" src="js/plugins/chart.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <?php
        $stmt = $conn->prepare("
        SELECT
        course_date_table.course_date as 'date',
        COUNT(attendance_table.attendance_status) as 'attendance'
        FROM attendance_table
        JOIN course_date_table ON course_date_table.course_date_id = attendance_table.attendance_course_date_id
        WHERE attendance_table.attendance_status='P' AND course_date_table.course_id=$course_id
        GROUP BY course_date_table.course_date_id
        ");
        $stmt->execute();
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        ?>

        <script>
            const attendanceLabels = [];
            const attendanceData = [];
        </script>

        <?php
        foreach((new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
        ?>
            <script>
                attendanceLabels.push("<?php echo $v['date']; ?>");
                attendanceData.push(<?php echo $v['attendance']; ?>);
            </script>
        <?php
        }
    ?>

    <script type="text/javascript">
        const dataAttendance = {
            labels: attendanceLabels,
            datasets: [{
                label: 'Attendance',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: attendanceData,
                barThickness: 30
            }]
        };

  const configAttendance = {
    type: 'bar',
    data: dataAttendance,
    options: {
        plugins: {
            legend: {
                display: false
            }
        },
        indexAxis: 'y',
        maintainAspectRatio: false,

    },
  };
  const attendanceChart = new Chart(
    document.getElementById('attendanceChart'),
    configAttendance
  );
      
  
  const dataAssessment = {
    labels: ["",""],
    datasets: [{
      label: 'Pre-Assessment',
      backgroundColor: '#084DB4',
      borderColor: '#084DB4',
      data: [<?php echo $course[4]; ?>,0],
      barThickness: 30

    },{
      label: 'Post-Assessment',
      backgroundColor: '#DA3636',
      borderColor: '#DA3636',
      data: [0,<?php echo $course[5]; ?>],
      barThickness: 30

    }]
  };
  const configAssessment = {
    type: 'bar',
    data: dataAssessment,
    options: {
        maintainAspectRatio: false,
    }
  };
  const assesmentChart = new Chart(
    document.getElementById('assesmentChart'),
    configAssessment
  );

  const dataFeedback = {
  labels: [
    'Excellent',
    'Good',
    'Average',
    'Poor'
  ],
  datasets: [{
    label: 'Feedback Chart',
    data: [11,2,1,0],
    backgroundColor: [
      '#16a5f2',
      '#f2bf16',
      'green',
      '#ab0c0c'
    ],
    hoverOffset: 4
  }]
};

const configFeedback = {
  type: 'pie',
  data: dataFeedback,
  options: {
        plugins: {
            legend: {
                display: false
            }
        },
        maintainAspectRatio: false,
    },
};
const feedbackChart1 = new Chart(
    document.getElementById('feedbackchart1'),
    configFeedback
  );
  const feedbackChart2 = new Chart(
    document.getElementById('feedbackchart2'),
    configFeedback
  );
  const feedbackChart3 = new Chart(
    document.getElementById('feedbackchart3'),
    configFeedback
  );
  const feedbackChart4 = new Chart(
    document.getElementById('feedbackchart4'),
    configFeedback
  );
    </script>
</body>

</html>