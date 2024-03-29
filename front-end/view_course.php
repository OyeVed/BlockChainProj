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
  <link rel="stylesheet" type="text/css"
    href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- multi date picker links -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css"
    rel="stylesheet" />
  <script type="text/javascript">
    jQuery(function ($) {

      $(document).ready(function () {
        $('#datepicker3').datepicker({
          startDate: new Date(),
          multidate: true,
          format: "dd/mm/yyyy",
          language: 'en',
          // beforeShow: function (input, inst) {
          // var rect = input.getBoundingClientRect();
          // setTimeout(function () {
          //   inst.dpDiv.css({ top: rect.top + 40, left: rect.left + 0 });
          // }, 0);
          // }
        }).on('changeDate', function (e) {
          // `e` here contains the extra attributes
          $(this).find('.input-group-addon .count').text(' ' + e.dates.length);
        });
      });
    });
      window.onload = function()
      {
        document.getElementById('markAttendanceFormId').reset()
        document.getElementById('edit_course_form_id').reset()
        document.getElementById('uploadPostAssessmentId').reset()
        document.getElementById('editStudentModalId').reset()
        document.getElementById('uploadPreAssessmentId').reset()

      }
  </script>
  <!-- export to excel -->
  <script lang="javascript" src="js/xlsx.full.min.js"></script>
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
          user_tables.name AS 'trainer',
          (SELECT COUNT(course_date_table.course_date_id) FROM course_date_table WHERE course_date_table.course_id=course_table.course_id) 'duration',
          course_table.course_batch_code,
          course_table.course_training_code
          FROM course_table
          JOIN student_table ON student_table.student_course_id = course_table.course_id
          LEFT JOIN user_tables ON user_tables.id = course_table.course_trainer_id
          JOIN course_date_table ON course_date_table.course_id = course_table.course_id
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


        $query = "
          SELECT
          student_table.student_id 'ID',
          student_table.student_name 'NAME',
          student_table.student_position 'POSITION',
          student_table.student_email 'EMAIL',
          student_table.student_emp_no 'EMP_NO',
          student_table.student_final_attendance 'FINAL_ATTENDANCE',
          (SELECT COUNT(attendance_table.attendance_status) FROM attendance_table WHERE attendance_table.attendance_student_id=student_table.student_id AND attendance_table.attendance_status='P') 'FINAL_ATTENDANCE_COUNT',
          student_table.student_phonenumber 'CONTACT_NO',
          student_table.student_division 'DIVISION',
          student_table.student_region 'REGION',
          student_table.student_manager_name 'MANAGER_NAME',
          GROUP_CONCAT(attendance_table.attendance_status) 'ATTENDANCE',
          student_table.student_pre_assesment_score 'PRE_ASSESMENT_SCORE',
          student_table.student_post_assesment_score 'POST_ASSESMENT_SCORE'
          FROM student_table
          LEFT JOIN attendance_table on attendance_table.attendance_student_id=student_table.student_id
          WHERE student_table.student_course_id=$course_id
          GROUP BY student_table.student_id
          ";

            $stmt = $conn->prepare($query);
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $students = array();

            foreach((new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
              array_push($students, array(
                  $v['ID'],
                  $v['NAME'],
                  $v['POSITION'],
                  $v['EMAIL'],
                  $v['FINAL_ATTENDANCE'],
                  explode(",", $v['ATTENDANCE']),
                  $v['PRE_ASSESMENT_SCORE'],
                  $v['POST_ASSESMENT_SCORE'],
                  $v['EMP_NO'],
                  $v['CONTACT_NO'],
                  $v['DIVISION'],
                  $v['REGION'],
                  $v['MANAGER_NAME'],
                )
              );
            }
            $selected_student_id = 0;

      ?>
    <div class="app-title">
      <div style="width: 100%;">
        <h1 style="display: inline;">Course Details:-</h1>
        <span style="float: right;">
          <button class="btn btn-outline-success" data-toggle="modal" data-target="#feedback_course_modal"  onclick='preAssessment(<?php echo $course_id; ?>, "<?php echo $course[1]; ?>")'>Feedback</button>
          <button class="btn btn-outline-success" data-toggle="modal" data-target="#pre_assesment_course_modal"
            onclick='preAssessment(<?php echo $course_id; ?>, "<?php echo $course[1]; ?>")'>Pre-Assessment</button>
          <button class="btn btn-outline-success" data-toggle="modal" data-target="#post_assesment_course_modal"
            onclick='postAssessment(<?php echo $course_id; ?>, "<?php echo $course[1]; ?>")'>Post-Assessment</button>
          <a href="view_report.php?courseid=<?php echo $course_id; ?>" class="btn btn-outline-success" >View Reports</a>

          <?php

            $export_csv_labels = [
              'Batch Code',
              'Staff Name',
              'Staff Employee Number',
              'Training Title',
              'Training Code',
              'Batch Trainer',
              'Batch Start Date',
              'Staff Email',
              'Certificate Name'
            ];

            $query = "
            SELECT
            course_date_table.course_date
            FROM course_date_table
            WHERE course_date_table.course_id=$course_id
            ORDER BY course_date_table.course_date
            LIMIT 1
            ";

            $stmt = $conn->prepare($query);
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            foreach((new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
              $course_start_date = $v['course_date'];
            }
            
            $export_csv_data = array();
            
            foreach ($students as $student) {
              if($student[4] === 'P'){
                array_push($export_csv_data,
                  [
                    $course[8],
                    $student[1],
                    $student[0],
                    $course[1],
                    $course[9],
                    $course[6],
                    $course_start_date,
                    $student[3],
                    str_replace(" ", "_", $student[1]).".pdf"
                  ]
                );
              }
            }
          ?>
          
          <button class="btn btn-outline-success" onclick='exportToCsv({labels:<?php echo json_encode(array_values($export_csv_labels)); ?>, data:<?php echo json_encode(array_values($export_csv_data)); ?>})' >Export Certificates</button>
        </span>
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
            <form action="/BlockChainProj/back-end/edit_course.php" id="edit_course_form_id" method="POST" enctype="multipart/form-data">
              <input type="hidden" class="form-control" name="course-id" id="edit_course_course_id"
                placeholder="Course Id" value="<?php echo $course_id ?>" />
                <input type="text" class="form-control" name="course-batch-code" id="edit_course_course_batch_code" placeholder="Batch Code"/><br>
            <input type="text" class="form-control" name="course-training-code" id="edit_course_course_training_code" placeholder="Training Code"/><br>
              <input type="text" class="form-control" name="course-name" id="edit_course_course_name"
                placeholder="Course Name" value="" /><br>
              <div class="form-group">
                <select class="form-control" id="exampleSelect1" name="course-trainer">
                  <option value="">Select Trainer Name</option>
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
                      <option value="<?php echo $v['trainer_id'] ?>" <?php if($v['trainer_name'] == $course[6]){echo "selected";} ?>><?php echo $v['trainer_name'] ?></option>
                    <?php
                    }
                  ?>
                </select>
              </div>
              <!-- <div class="input-group date form-group " style="margin-top: 60px;" id="datepicker3">
                <input type="text" class="form-control" id="Dates" style="margin-top: -40px;" name="Dates"
                  placeholder="Course Dates" required />
                <span class="input-group-addon" style="margin-top: -35px;margin-left:5px;"><i
                    class="glyphicon glyphicon-calendar fa fa-calendar"></i><span class="count"></span></span>
              </div> -->
              <div class="modal-footer " style="justify-content: center;">
                <button class="btn btn-success " type="submit">Save</button>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
    <!-- feedback modal -->
    <div class="modal fade" id="feedback_course_modal" role="dialog">
      <div class="modal-dialog  modal-dialog-centered ">

        <!-- Modal content-->

        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Upload Feedback Report<br />
              <p style="text-align:left; font-size:13px;font-weight:500;" id="pre_assesment_course_name">
              <p>
            </h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <form action="/BlockChainProj/back-end/feedback.php" id="uploadPreAssessmentId" method="POST" enctype="multipart/form-data">
              <input type="hidden" class="form-control" name="course-id" placeholder="Course Id" value="<?php echo $course_id; ?>" id="pre_assesment_course_id" /><br>
              <p style="text-align:left; font-size:14px; margin-top: -20px;" for="exampleInputFile">Import File</p>
              <input class="form-control-file" style="font-size:14px;" name="feedback-file" id="exampleInputFile"
                type="file" accept=".xlsx" aria-describedby="fileHelp"><small class="form-text text-muted"
                id="fileHelp"><a href="./files/feedback.xlsx" download>Download Sample</a></small>
              <div class="modal-footer " style="justify-content: center;margin-top:20px;">
                <button class="btn btn-success " type="submit">Upload</button>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
    <!-- pre assesment modal -->
    <div class="modal fade" id="pre_assesment_course_modal" role="dialog">
      <div class="modal-dialog  modal-dialog-centered ">

        <!-- Modal content-->

        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Upload Pre-Assessment Report<br />
              <p style="text-align:left; font-size:13px;font-weight:500;" id="pre_assesment_course_name">
              <p>
            </h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <form action="/BlockChainProj/back-end/pre_assessment.php" id="uploadPreAssessmentId" method="POST" enctype="multipart/form-data">
              <input type="hidden" class="form-control" name="course-id" placeholder="Course Id" value="<?php echo $course_id; ?>"
                id="pre_assesment_course_id" /><br>
              <p style="text-align:left; font-size:14px; margin-top: -20px;" for="exampleInputFile">Import File</p>
              <input class="form-control-file" style="font-size:14px;" name="pre-assessment-file" id="exampleInputFile"
                type="file" accept=".xlsx" aria-describedby="fileHelp"><small class="form-text text-muted"
                id="fileHelp"><a href="./files/pre assessment report.xlsx" download>Download Sample</a></small>
              <div class="modal-footer " style="justify-content: center;margin-top:20px;">
                <button class="btn btn-success " type="submit">Upload</button>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
    <!-- post assesment modal -->
    <div class="modal fade" id="post_assesment_course_modal" role="dialog">
      <div class="modal-dialog  modal-dialog-centered ">

        <!-- Modal content-->

        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Upload Post-Assessment Report<br />
              <p style="text-align:left; font-size:13px;font-weight:500;" id="post_assesment_course_name">
              <p>
            </h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <form action="/BlockChainProj/back-end/post_assessment.php" id="uploadPostAssessmentId" method="POST" enctype="multipart/form-data">
              <input type="hidden" class="form-control" name="course-id" placeholder="Course Id" value="<?php echo $course_id; ?>"
                id="post_assesment_course_id" /><br>
              <p style="text-align:left; font-size:14px; margin-top: -20px;" for="exampleInputFile">Import File</p>
              <input class="form-control-file" style="font-size:14px;" name="post-assessment-file" id="exampleInputFile"
                type="file" accept=".xlsx" aria-describedby="fileHelp"><small class="form-text text-muted"
                id="fileHelp"><a href="./files/post assessment report.xlsx" download>Download Sample</a></small>
              <div class="modal-footer " style="justify-content: center;margin-top:20px;">
                <button class="btn btn-success " type="submit">Upload</button>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
    <!-- edit student -->
    <div class="modal fade" id="edit_student_Modal" role="dialog">
      <div class="modal-dialog  modal-dialog-centered modal-lg">

        <!-- Modal content-->

        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Edit Student</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <form action="/BlockChainProj/back-end/edit_student.php" id="editStudentModalId" method="POST" enctype="multipart/form-data">
              <input type="hidden" class="form-control" name="student-id" id="student_id" placeholder="Student Id" value="" />
              <div class="container">
                <div class="row justify-content-between ">
                  <div class="col">
                    <input type="text" class="form-control" name="student-name" id="student_name" id="edit_student_name"
                      placeholder="Student Name" value="" /><br>
                    <input type="email" class="form-control" name="student-email" id="edit_student_email"
                      placeholder="Email" value="" /><br>
                    <input type="text" class="form-control" name="student-employee-no" id="edit_student_employee_no"
                      placeholder="Employee No." value="" /><br>
                    <input type="text" class="form-control" name="student-division" id="edit_student_division"
                      placeholder="Division" value="" /><br>
                    <input type="text" class="form-control" name="student-manager-name" id="edit_student_manager-name"
                      placeholder="Manager Name" value="" /><br>
                  </div>
                  <div class="col">
                    <input type="text" class="form-control" name="student-position" id="edit_student_position"
                      placeholder="Position" value="" /><br>
                    <input type="text" class="form-control" name="student-final-attendance"
                      id="edit_student_final_attendance" placeholder="Final Attendance" value="" readonly /><br>
                    <input type="text" class="form-control" name="student-contact-no" id="edit_student_contact_no"
                      placeholder="Contact No." value="" /><br>
                    <input type="text" class="form-control" name="student-region" id="edit_student_region"
                      placeholder="Region" value="" /><br>
                  </div>
                </div>
                <label for="">Day Wise Attendance:</label>
                <!-- <div class="d-flex w-100 justify-content-around text-center" > -->
                <div class="col-12 d-flex justify-content-around align-items-center mt-3">
                  <label for="" class="ml-2">Date</label>
                  <label for="">Present/Absent</label>
                </div>
                <div id="dateWiseAttendance" >

                </div>
              </div>

              <div class="modal-footer " style="justify-content: center;">
                <button class="btn btn-success " type="submit">Save</button>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
    <!-- mark attendance modal -->
    <div class="modal fade" id="mark_attendance_modal" role="dialog">
      <div class="modal-dialog  modal-dialog-centered ">

        <!-- Modal content-->

        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Mark Attendance<br />
              <p style="text-align:left; font-size:13px;font-weight:500;" id="mark_attendance_course_name">
              <p>
            </h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <form action="/BlockChainProj/back-end/attendance.php"  id="markAttendanceFormId" method="POST" enctype="multipart/form-data">
              <input type="hidden" class="form-control" name="course-id" placeholder="Course Id" value="<?php echo $course_id; ?>"
                id="mark_attendance_course_id" /><br>
              <div class="form-group">
                <select name="course-date" class="form-control" id="exampleSelect1">
                  <option value="0">Select Date</option>
                  <?php
                    $query = "
                    SELECT
                    course_date_table.course_date_id AS 'id',
                    course_date_table.course_date AS 'date'
                    FROM course_date_table
                    WHERE course_date_table.course_id=$course_id
                    ";

                    $stmt = $conn->prepare($query);
                    $stmt->execute();
                    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

                    $course_dates = array();

                    foreach((new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
                      $date = explode("-", $v['date']);

                      $date = "$date[2]-$date[1]-$date[0]";

                      array_push($course_dates, array("id" => $v['id'], "date" => $date));
                      
                      echo "<option value='".$v['id']."'>".$date."</option>";
                    }
                  ?>
                </select>
              </div>
              <div class="form-group">
              </div>
              <div class="d-flex justify-content-between" >
               <label for="">Mark Attendance:</label>
               <div>
                 <input name='mark_all' type='checkbox' onclick="handleMarkAllAttendance()" id="mark_all_attendance_input"  class='ml-4'> 
                 <label for='mark_all' class='ml-2'>Mark All</label>
              </input>
               </div>
               </div>
              <div style="max-height:200px;overflow:auto;">
                <div class="col-12 d-flex justify-content-around align-items-center mt-3">
                  <label class="">Student Name</label>
                  <label >Present/Absent</label>
                </div>
                
                <?php
                  $query = "
                  SELECT
                  student_table.student_id AS 'id',
                  student_table.student_name AS 'name'
                  FROM student_table
                  WHERE student_table.student_course_id=$course_id
                  ";

                  $stmt = $conn->prepare($query);
                  $stmt->execute();
                  $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

                  foreach((new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
                    echo "<div class='col-12 d-flex justify-content-around align-items-center mt-3'>";
                    echo "<label for='$v[id]' class='ml-2'>$v[name]</label>";
                    echo "<input name='$v[id]' id='mark_attendance_student_$v[id]' type='checkbox' class='ml-4'>";
                    echo "</div>";
                  }
                ?>

              </div>

              <div class="modal-footer mt-3" style="justify-content: center;">
                <input type="submit" class="btn btn-success" value="Mark Attendance">
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
            <div class="mb-1">
              <h1 style="display: inline;">
                <?php echo $course[1] ?>
              </h1>
              <span style="float: right;">
                <i onclick='editCourse(<?php echo "[$course[0], \"$course[1]\", $course[2], \"$course[8]\", \"$course[9]\" ]"; ?>)' data-toggle="modal"
                  data-target="#edit_course_Modal" class="fa fa-pencil-square-o btn btn-warning text-light "
                  aria-hidden="true"></i>
                <i onclick='deleteCourse(<?php echo $course_id; ?>, "<?php echo $course[1]; ?>")'
                  class="fa fa-trash btn btn-danger" aria-hidden="true"></i>
              </span>
            </div>
            <div class="mt-2 mb-4">
              <p style="display: inline;">Total Students Enrolled:
                <?php echo $course[2]; ?>
              </p><br />
              <p style="display: inline;">Trainer Name:
                <?php echo $course[6]; ?>
              </p><br />
              <p style="display: inline;">Duration: <?php echo $course[7] ?> Days</p><br />
            </div>
            <div class="mb-3" style="margin-top: 60px;">
              <h5 style="display: inline;">Students Attendance Table</h5>
              <button class="btn btn-outline-success" data-toggle="modal" data-target="#mark_attendance_modal"
                style="float: right;"
                onclick='markAttendance(<?php echo $course_id; ?>, "<?php echo $course[1]; ?>")'>Mark
                Attendance</button>
            </div>
            <div class="table-responsive mt-4">
              <table class="table table-hover table-bordered" id="sampleTable">
                <thead>
                  <tr>
                    <th>Sr.No</th>
                    <th style="white-space: nowrap;">Student Name</th>
                    <th>Position</th>
                    <th>Email</th>
                    <th>Final Attendance</th>
                    <?php
                      foreach ($course_dates as $key => $value) {
                        echo "<th style='min-width: 100px;'>" . $value['date'] . "</th>";
                      }
                    ?>
                    <th>Pre Assesment Score</th>
                    <th>Post Assesment Score</th>
                    <th>Employee No.</th>
                    <th>Contact No.</th>
                    <th>Division</th>
                    <th>Region</th>
                    <th>Manager Name</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>

                  <?php
                  
                    $students_js = array();
                    foreach ($students as $student) {
                      array_push($students_js, array("id" => $student[0], "name" => $student[1]));
                      echo "<tr>";
                      foreach ($student as $value) {
                        if(is_array($value)){
                          foreach ($value as $val) {
                            echo "<td>".$val."</td>";
                          }
                        }else{
                          echo "<td>".$value."</td>";
                        }
                      }

                      $course_dates_js = json_encode($course_dates);

                      $student_attendance_js = json_encode(array_values($student[5]));
                      
                      echo  "<th><i onclick='editStudent([\"$student[0]\",\"$student[1]\", \"$student[2]\", \"$student[3]\", \"$student[4]\", \"$student[8]\", \"$student[9]\", \"$student[10]\", \"$student[11]\", \"$student[12]\", $course_dates_js, $student_attendance_js])' data-toggle='modal' data-target='#edit_student_Modal' class='fa fa-pencil-square-o ml-1' style='cursor:pointer;' aria-hidden='true'></i><i onclick='deleteStudent($student[0], \"$student[1]\", $course_id)' class='fa fa-trash-o ml-3' style='cursor:pointer;' aria-hidden='true'></i></th>";
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
    <div class="table-b" style="display: none;" ></div>
  </main>

  <!-- Essential javascripts for application to work-->
  <script>
    function myFunction() {
      var x = document.getElementById("adduser");
      var asd = location.hostname;
      var asd = 'http://' + asd + '/BlockChainProj/front-end/';
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

  <!-- <script>
      $('.date-selector').datepicker({
        multidate: true,
        format: 'dd-mm-yyyy'
      });
    </script> -->
<script>
  function editStudent(student){
    console.log(student);
    document.getElementById('student_id').value = student[0];
    document.getElementById('student_name').value = student[1];
    document.getElementById('edit_student_position').value = student[2];
    document.getElementById('edit_student_email').value = student[3];
    document.getElementById('edit_student_final_attendance').value = student[4];
    document.getElementById('edit_student_employee_no').value = student[5];
    document.getElementById('edit_student_contact_no').value = student[6];
    document.getElementById('edit_student_division').value = student[7];
    document.getElementById('edit_student_region').value = student[8];
    document.getElementById('edit_student_manager-name').value = student[9];
    let dateWiseAttendanceDiv = document.getElementById('dateWiseAttendance');
    let dateWiseArray = student[10]
    let dateWiseAttenArray = student[11]
    dateWiseAttendanceDiv.innerHTML = ''
    if(dateWiseArray)
    {

      for(var i = 0; i < dateWiseArray?.length ; i++)
      {
        let valNowTee = dateWiseAttenArray[i] === 'P' ? true : false
         if(valNowTee)
         {
           dateWiseAttendanceDiv.innerHTML = dateWiseAttendanceDiv.innerHTML +

           `<div class='col-12 d-flex justify-content-around align-items-center mt-3'><label for='${dateWiseArray[i]['id']}' class='ml-2'>${dateWiseArray[i]['date']}</label><input name='${dateWiseArray[i]['id']}' type='checkbox' checked class='ml-4'></div>`
         }else{
          dateWiseAttendanceDiv.innerHTML = dateWiseAttendanceDiv.innerHTML +

`<div class='col-12 d-flex justify-content-around align-items-center mt-3'><label for='${dateWiseArray[i]['id']}' class='ml-2'>${dateWiseArray[i]['date']}</label><input name='${dateWiseArray[i]['id']}' type='checkbox'  class='ml-4'></div>`
         }
      }
    }
  }
</script>
<!-- handle mark all attendance -->
<script>
    var students = <?php echo json_encode($students_js); ?>;
    function handleMarkAllAttendance()
    {
      if(document.getElementById('mark_all_attendance_input').checked)
      {
        students?.forEach(student =>{
          document.getElementById(`mark_attendance_student_${student['id']}`).checked = true
        })
      }else{
        students?.forEach(student =>{
          document.getElementById(`mark_attendance_student_${student['id']}`).checked = false
        })
      }
    }
  </script>
  <script>
  
  
exportToCsv = function(course) {
  var Results = [
course.labels,
...course.data
];
const worksheet1 = XLSX.utils.aoa_to_sheet(Results);
document.querySelector('.table-b').innerHTML = XLSX.utils.sheet_to_html(worksheet1, { id: 'table2', header: 'sheet1' });  
const table = document.querySelector('#table2');
            const workbook = XLSX.utils.table_to_book(table, { sheet: 'sheet1' });
            return XLSX.writeFile(workbook, 'certificates.xlsx');


}

  </script>
</body>

</html>