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
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="app sidebar-mini">
  <!-- Navbar-->
  <header class="app-header">
    <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
    <!-- Navbar Right Menu-->
    <ul class="app-nav">
      <h6 class="app-header__logo1">Technology Academy</h6>
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
    foreach ((new RecursiveArrayIterator($stmt->fetchAll())) as $k => $v) {
      $name = $v["name"];
    }
    ?>
    <div>
      <p class="app-menu__label" style="padding-left:40px; color:white; font-size:larger;">
        <?php echo "Hello, " . "$name"; ?>
      </p>
    </div>
    <ul class="app-menu">
      <li><a class="app-menu__item" href="dashboard.php"><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Dashboard</span></a></li>
      <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-edit"></i><span class="app-menu__label">Issue Certificates</span><i class="treeview-indicator fa fa-angle-right"></i></a>
        <ul class="treeview-menu">
          <li><a class="treeview-item" href="single_certificate.php"><i class="icon fa fa-circle-o"></i>Single
              Certificate</a></li>
          <li><a class="treeview-item" href="bulk_certificate.php"><i class="icon fa fa-circle-o"></i>Bulk
              Certificates</a></li>
        </ul>
      </li>
      <li><a class="app-menu__item" href="view_certificate.php"><i class="app-menu__icon fa fa-cubes"></i><span class="app-menu__label">View Certificates</span></a></li>
      <li><a class="app-menu__item" href="view_user.php"><i class="app-menu__icon fa fa-id-card"></i><span class="app-menu__label">View Users</span></a></li>
      <li><a class="app-menu__item active" href="view_courses.php"><i class="app-menu__icon fa fa-graduation-cap"></i><span class="app-menu__label">View Courses</span></a></li>
  </aside>
  <main class="app-content">
    <?php

    if (!isset($_GET['courseid'])) {
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
          (SELECT COUNT(course_date_table.course_date_id) FROM course_date_table WHERE course_date_table.course_id=course_table.course_id) 'duration'
          FROM course_table
          JOIN student_table ON student_table.student_course_id = course_table.course_id
          LEFT JOIN user_tables ON user_tables.id = course_table.course_trainer_id
          WHERE course_table.course_id = $course_id
          GROUP BY student_table.student_course_id;
          ");
      $stmt->execute();
      $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

      $course = array();

      foreach ((new RecursiveArrayIterator($stmt->fetchAll())) as $k => $v) {
        foreach ($v as $key => $value) {
          array_push($course, $value);
        }
      }
      if (count($course) == 0) {
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
          <button class="btn btn-outline-success" onclick="window.print()">Download Report</button>
          <button onclick="window.location.href = 'view_course.php?courseid=<?php echo $course_id; ?>' " class="btn btn-outline-success">View Course</button>
        </span>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="tile" id="courseDetailsTileId">
          <div class="tile-body">
            <div class="mb-1">
              <h1><?php echo $course[1]; ?></h1>
              <p>Total Students Enrolled: <?php echo $course[2]; ?><br>Trainer Name: <?php echo $course[6]; ?><br>Duration: <?php echo $course[7]; ?> Days</p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8 ">
            <div class="tile">
              <h6 class="tile-title">Attendance :-</h6>
              <div>

                <canvas id="attendanceChart"></canvas>
              </div>
            </div>
          </div>
          <div class="col-4 ">
            <div class="tile" style="height: 94%;">
              <h6 class="tile-title">Assessment :-</h6>
              <div style="height: 88%;">
                <canvas id="assesmentChart"></canvas>
              </div>
            </div>
          </div>
        </div>
        <div class="tile">
          <h6 class="tile-title">Students :-</h6>
          <table class="table table-hover table-bordered" id="sampleTable">
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
                        student_table.student_final_attendance 'final_attendance',
                        (SELECT COUNT(attendance_table.attendance_status) FROM attendance_table WHERE attendance_table.attendance_student_id=student_table.student_id AND attendance_table.attendance_status='P') 'final_attendance_count'
                        FROM student_table
                        WHERE student_table.student_course_id = $course_id
                        ");
              $stmt->execute();
              $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

              foreach ((new RecursiveArrayIterator($stmt->fetchAll())) as $k => $v) {
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
        <div class="tile ">
          <h4>Feedback :-</h4>
          <div class="row mt-3">
            <?php
            $stmt = $conn->prepare("
                  SELECT
                  feedback_table.feedback_id AS 'feedback_id',
                  feedback_table.feedback_question AS 'feedback_question',
                  GROUP_CONCAT(feedback_response_table.feedback_response) AS 'feedback_responses'
                  FROM feedback_table
                  JOIN feedback_response_table ON feedback_response_table.feedback_id = feedback_table.feedback_id
                  WHERE feedback_table.feedback_course_id = $course_id
                  GROUP BY feedback_table.feedback_id
                  ");
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            function php_to_js_array($array)
            {
              $js_array = json_encode($array);
              return $js_array;
            }
            $all_fetched_feedback = new RecursiveArrayIterator($stmt->fetchAll());
            foreach ($all_fetched_feedback as $k => $v) {

              $feedback_responses = explode(",", $v['feedback_responses']);

              $feedback_labels_array = array_values(array_unique($feedback_responses));
              $feedback_values_array = array_values(array_count_values($feedback_responses));

              $feedback_labels = php_to_js_array($feedback_labels_array);
              $feedback_values = php_to_js_array($feedback_values_array);
              if (sizeof($all_fetched_feedback) - 1 == $k) {
                echo ("<script>console.log(`" . json_encode($v) . "`);</script>");
                $integerIDs = array_map('intval', explode(',', $v['feedback_responses']));
                $floatValNow = array_sum($integerIDs) /  (float) sizeof($integerIDs);
                echo ("<script>console.log(`" . json_encode($integerIDs) . "`);</script>");
                echo ("<script>console.log(`" . $floatValNow . "`);</script>");
            ?>
                <div class="col-12 mb-4">
                  <p class="mb-4"><b><?php echo $v['feedback_question'] ?></b></p>
                  <div class="row mb-5">
                    <div class="col-6 d-flex justify-content-center align-items-center flex-column">
                      <h4><?php echo sizeof($integerIDs) ?></h4>
                      <p>Responses</p>
                    </div>
                    <div class="col-6 d-flex justify-content-center align-items-center flex-column">
                      <p style="color: orange;font-size:30px;">
                        <?php
                        for ($i = 1; $i < 11; $i++) {
                          if ($i <= $floatValNow) {
                        ?>
                            <span class="fa fa-star"></span>
                          <?php
                          } else {
                          ?>
                            <span class="fa fa-star-o"></span>
                        <?php
                          }
                        }
                        ?>
                      </p>
                      <span style="font-size: 15px;"><?php echo $floatValNow ?> Average Rating</span>
                    </div>
                  </div>
                </div>
              <?php
              } elseif (sizeof($all_fetched_feedback) - 2 == $k) {
                // echo ("<script>console.log(`" . json_encode($v) . "`);</script>");
                $arr_response_str = preg_split("/\,/", $v['feedback_responses']);
              ?>
                <div class="col-12 mb-4">
                  <p class="mb-4"><b><?php echo $v['feedback_question'] ?></b></p>
                  <div class="row mb-5">
                    <div class="col-6 d-flex justify-content-center align-items-center flex-column">
                      <h4><?php echo sizeof($arr_response_str) ?></h4>
                      <p>Responses</p>
                    </div>
                    <div class="col-6 d-flex justify-content-center align-items-center flex-column">
                      <p style="color:gray;">Latest Response</p>
                      <p>"<?php echo $arr_response_str[sizeof($arr_response_str) - 1] ?>"</p>
                    </div>
                  </div>
                </div>
              <?php
              } else {
              ?>
                <div class="col-6 mb-4">
                  <div class="d-flex justify-content-between" style="min-height: 200px;">
                    <div style="flex-basis: 55%;">
                      <p><b><?php echo $v['feedback_question'] ?></b></p>
                      <table style="width: 200px;">

                        <?php
                        for ($i = 0; $i < sizeof($feedback_labels_array); $i++) {
                        ?>
                          <tr>
                            <td><?php echo $feedback_labels_array[$i]; ?></td>
                            <td>: </td>
                            <td><?php echo $feedback_values_array[$i]; ?></td>
                          </tr>
                        <?php
                        }
                        ?>

                      </table>
                    </div>
                    <div style="flex-basis: 40%;height:120px;">
                      <canvas id="<?php echo $v['feedback_id']; ?>"></canvas>
                    </div>
                  </div>
                </div>

                <script>
                  new Chart(
                    document.getElementById('<?php echo $v['feedback_id']; ?>'), {
                      type: 'pie',
                      data: {
                        labels: <?php echo $feedback_labels; ?>,
                        datasets: [{
                          label: 'Feedback Chart',
                          data: <?php echo $feedback_values; ?>,
                          backgroundColor: [
                            '#16a5f2',
                            '#f2bf16',
                            'green',
                            '#ab0c0c'
                          ],
                          hoverOffset: 4
                        }]
                      },
                      options: {
                        plugins: {
                          legend: {
                            display: false
                          }
                        },
                        maintainAspectRatio: false,
                      },
                    }
                  );
                </script>
            <?php
              }
            }
            ?>
          </div>
          <div>
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
  <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->

  <?php
  $stmt = $conn->prepare("
        SELECT
        CONCAT(DAY(course_date_table.course_date), '/', MONTH(course_date_table.course_date), '/', YEAR(course_date_table.course_date)) as 'date',
        ((COUNT(attendance_table.attendance_status) / course_table.course_student_count) * 100) as 'attendance',
        course_table.course_student_count as 'student_count'
        FROM attendance_table
        JOIN course_date_table ON course_date_table.course_date_id = attendance_table.attendance_course_date_id
        JOIN course_table ON course_table.course_id = course_date_table.course_id
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
  foreach ((new RecursiveArrayIterator($stmt->fetchAll())) as $k => $v) {
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
        maxBarThickness: 30
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

      },
    };
    const attendanceChart = new Chart(
      document.getElementById('attendanceChart'),
      configAttendance
    );


    const dataAssessment = {
      labels: ["", ""],
      datasets: [{
        label: 'Pre-Assessment',
        backgroundColor: '#084DB4',
        borderColor: '#084DB4',
        data: [<?php echo $course[4]; ?>, 0],
        barThickness: 30

      }, {
        label: 'Post-Assessment',
        backgroundColor: '#DA3636',
        borderColor: '#DA3636',
        data: [0, <?php echo $course[5]; ?>],
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
        data: [11, 2, 1, 0],
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