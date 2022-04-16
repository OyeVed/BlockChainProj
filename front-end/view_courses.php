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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script> 
    <title>Blockchain Based Certificate Verification</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- multi date picker links -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css"
      rel="stylesheet"
    />
    <script type="text/javascript">
        jQuery(function($) {
          $(document).ready(function() {
    $('#datepicker').datepicker({
        startDate: new Date(),
        multidate: true,
        format: "dd/mm/yyyy",
        language: 'en'
    }).on('changeDate', function(e) {
        // `e` here contains the extra attributes
        $(this).find('.input-group-addon .count').text(' ' + e.dates.length);
    });
});
$(document).ready(function() {
    $('#datepicker2').datepicker({
        startDate: new Date(),
        multidate: true,
        format: "dd/mm/yyyy",
        language: 'en'
    }).on('changeDate', function(e) {
        // `e` here contains the extra attributes
        $(this).find('.input-group-addon .count').text(' ' + e.dates.length);
    });
});
         });
    </script>
  </head>
  <body class="app sidebar-mini">
    <!-- Navbar-->
    <header class="app-header">
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
      <div><p class="app-menu__label" style="padding-left:40px; color:white; font-size:larger;"><?php echo "Hello, "."$name";?></p></div>
      <ul class="app-menu">
        <li><a class="app-menu__item" href="dashboard.php"><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Dashboard</span></a></li>
        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-edit"></i><span class="app-menu__label">Issue Certificates</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
            <li><a class="treeview-item" href="single_certificate.php"><i class="icon fa fa-circle-o"></i>Single Certificate</a></li>
            <li><a class="treeview-item" href="bulk_certificate.php"><i class="icon fa fa-circle-o"></i>Bulk Certificates</a></li>
          </ul>
        </li>
        <li><a class="app-menu__item" href="view_certificate.php"><i class="app-menu__icon fa fa-cubes"></i><span class="app-menu__label">View Certificates</span></a></li>
        <li><a class="app-menu__item" href="view_user.php"><i class="app-menu__icon fa fa-id-card"></i><span class="app-menu__label">View Users</span></a></li>
        <li><a class="app-menu__item active" href="view_courses.php"><i class="app-menu__icon fa fa-graduation-cap"></i><span class="app-menu__label">View Courses</span></a></li>
    </aside>
    <main class="app-content">
      <div class="app-title">
        <div style="width: 100%;">
          <h1 style="display: inline;">All Courses:-</h1>
          <span style="float: right;">
            <button class="btn btn-outline-success" data-toggle="modal" data-target="#myModal">Add Course</button>
          </span>
        </div>
      </div>
      <!-- Add modal -->
      <div class="modal fade" id="myModal" role="dialog">
      <div class="modal-dialog  modal-dialog-centered ">
    
      <!-- Modal content-->
      
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add New Course</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
        <form action="/BlockChainProj/back-end/add_course.php" method="POST" enctype="multipart/form-data">
            <input type="text" class="form-control" name="course-name" placeholder="Course Name"/><br>
            <div class="form-group">
              <select class="form-control" id="exampleSelect1" name="course-trainer">
                <option value="" >Select Trainer Name</option>
                <?php
                  $stmt = $conn->prepare("
                  SELECT
                  id AS 'trainer_id',
                  name AS 'trainer_name'
                  FROM user_tables
                  ");
                  $stmt->execute();
                  $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                  ?>

                  <?php
                  foreach((new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
                  ?>
                    <option value="<?php echo $v['trainer_id'] ?>"><?php echo $v['trainer_name'] ?></option>
                  <?php
                  }
                ?>
              </select>
            </div>
            <div class="input-group date form-group" style="margin-top: 60px;" id="datepicker">
               <input type="text" class="form-control" id="Dates" name="course-dates" style="margin-top: -40px;" placeholder="Course Dates" required />
                <span class="input-group-addon"  style="margin-top: -35px;margin-left:5px;"  ><i class="glyphicon glyphicon-calendar fa fa-calendar"></i><span class="count"></span></span>
            </div>
            <div class="form-group mt-3">
                    <label for="exampleInputFile">Students</label>
                    <input class="form-control-file" name="students-file" id="exampleInputFile" type="file"  accept=".csv" aria-describedby="fileHelp"><small class="form-text text-muted" id="fileHelp">Import student list using .csv file. <a href="./files/students.csv" download>Download Sample</a></small>
                  </div>
           <div class="modal-footer " style="justify-content: center;" >
          <button class="btn btn-success " type="submit">Save</button>
        </div> 
          </form>
        </div>
      </div>
      
    </div>
  </div>
        <!-- edit modal -->
        <div class="modal fade" id="edit_course_Modal" role="dialog">
      <div class="modal-dialog  modal-dialog-centered ">
    
      <!-- Modal content-->
      
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Course</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
        <form action="/BlockChainProj/back-end/edit_course.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" class="form-control" name="course-id" id="edit_course_course_id" placeholder="Course Id" value="" />    
        <input type="text" class="form-control" name="course-name" id="edit_course_course_name" placeholder="Course Name" value=""/><br>
            <div class="form-group">
              <select class="form-control" id="exampleSelect1">
                <option  value="" >Select Trainer Name</option>
                  <?php
                    $stmt = $conn->prepare("
                    SELECT
                    id AS 'trainer_id',
                    name AS 'trainer_name'
                    FROM user_tables
                    ");
                    $stmt->execute();
                    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    ?>

                    <?php
                    foreach((new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
                    ?>
                      <option value="<?php echo $v['trainer_id'] ?>"><?php echo $v['trainer_name'] ?></option>
                    <?php
                    }
                  ?>
              </select>
            </div>
            <div class="input-group date form-group" id="datepicker2" style="margin-top: 60px;">
               <input type="text" class="form-control" id="Dates" style="margin-top: -40px;" name="Dates" placeholder="Course Dates" required />
                <span class="input-group-addon"  style="margin-top: -35px ;margin-left: 5px;"  ><i class="glyphicon glyphicon-calendar fa fa-calendar"></i><span class="count"></span></span>
            </div>
           <div class="modal-footer " style="justify-content: center;" >
          <button class="btn btn-success " type="submit">Save</button>
        </div> 
          </form>
        </div>
      </div>
      
    </div>
  </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body">
              <div class="table-responsive">
                <table class="table table-hover table-bordered" id="sampleTable">
                  <thead>
                    <tr>
                      <th style="vertical-align: middle; text-align:center; width: 50px;">ID</th>
                      <th style="vertical-align: middle; text-align:center;">Course Name</th>
                      <th style="vertical-align: middle; text-align:center;">Trainer Name</th>
                      <th style="vertical-align: middle; text-align:center; width: 80px;">Number of Students</th>
                      <th style="vertical-align: middle; text-align:center; width: 100px;">Average Attendance (%)</th>
                      <th style="vertical-align: middle; text-align:center; width: 120px;">Average Pre-Assessment (%)</th>
                      <th style="vertical-align: middle; text-align:center; width: 120px;">Average Post-Assessment (%)</th>
                      <th style="vertical-align: middle; text-align:center; width: 120px;">Course Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $stmt = $conn->prepare("
                    SELECT
                    course_table.course_id AS 'id',
                    course_table.course_name AS 'name',
                    user_tables.name AS 'trainer',
                    course_table.course_student_count AS 'no_of_students',
                    90 AS 'avg_attendance',
                    AVG(student_table.student_pre_assesment_score) AS 'avg_pre_assessment',
                    AVG(student_table.student_post_assesment_score) AS 'avg_post_assessment'
                    FROM course_table
                    JOIN student_table ON student_table.student_course_id = course_table.course_id
                    JOIN course_date_table ON course_date_table.course_id = course_table.course_id
                    JOIN attendance_table ON attendance_table.attendance_course_date_id = course_date_table.course_date_id
                    LEFT JOIN user_tables ON user_tables.id = course_table.course_trainer_id
                    GROUP BY student_table.student_course_id;
                    ");
                    $stmt->execute();
                    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

                    foreach((new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
                      echo "<tr style=\"cursor: pointer;\" >";
                      echo "<td style=\"vertical-align: middle; text-align:center; line-height: 100%;\" onclick=\" window.location.href= 'view_course.php?courseid=$v[id]' \" >".$v['id']."</td>";
                      echo "<td style=\"vertical-align: middle; text-align:center; line-height: 100%;\" onclick=\" window.location.href= 'view_course.php?courseid=$v[id]' \" >".$v['name']."</td>";
                      echo "<td style=\"vertical-align: middle; text-align:center; line-height: 100%;\" onclick=\" window.location.href= 'view_course.php?courseid=$v[id]' \" >".$v['trainer']."</td>";
                      echo "<td style=\"vertical-align: middle; text-align:center; line-height: 100%;\" onclick=\" window.location.href= 'view_course.php?courseid=$v[id]' \" >".$v['no_of_students']."</td>";
                      echo "<td style=\"vertical-align: middle; text-align:center; line-height: 100%;\" onclick=\" window.location.href= 'view_course.php?courseid=$v[id]' \" >".$v['avg_attendance']."</td>";
                      echo "<td style=\"vertical-align: middle; text-align:center; line-height: 100%;\" onclick=\" window.location.href= 'view_course.php?courseid=$v[id]' \" >".$v['avg_pre_assessment']."</td>";
                      echo "<td style=\"vertical-align: middle; text-align:center; line-height: 100%;\" onclick=\" window.location.href= 'view_course.php?courseid=$v[id]' \" >".$v['avg_post_assessment']."</td>";
                      echo "<td style=\"vertical-align: middle; text-align:center; line-height: 100%;\">
                              <i data-toggle=\"modal\" data-target=\"#edit_course_Modal\" onclick=\"editCourse([$v[id], '$v[name]', $v[no_of_students]])\"  class='fa fa-pencil-square-o btn btn-warning text-light pt-2 pb-2' aria-hidden='true' ></i>
                             <i onclick=\"deleteCourse($v[id], '$v[name]')\" class='fa fa-trash btn btn-danger text-light pt-2 pb-2' aria-hidden='true'></i>
                            </td>";
                      echo "</tr>";
                    }
                    ?>
                    
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
    <!-- Essential javascripts for application to work-->
    <script>
        function myFunction() {
          var x = document.getElementById("adduser");
          var asd = location.hostname;
          var asd = 'http://'+asd+'/BlockChainProj/front-end/';
          window.location.href = asd;
        }
    </script>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="js/plugins/pace.min.js"></script>
    <!-- Page specific javascripts-->
    <script src="js/popups.js"></script>
    <!-- Data table plugin-->
    <script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript">$('#sampleTable').DataTable();</script>
  </body>
</html>
