<?php
    session_start();
?>
<html>
<link rel="shortcut icon" type="image/x-icon" href="../front-end/images/android-icon-192x192.png">
<body style="background-color:#009688;">
</body>
</html>
<?php
// importing connection file and starting session.
require_once("common/connection.php");

try{

    $course_id = $_POST['course-id'];
    $course_date = $_POST['course-date'];

    if($course_date == 0){
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@8'></script><script type='text/javascript'>console.log('Error: ' );
            Swal.fire
                ({
                'title': 'Error',
                'text': 'Course Date is not selected',
                'type': 'Error'
                })
        </script>";
        echo "<script>
            setTimeout(function() {
                window.history.go(-1);
            }, 2000);
        </script>";
    }

    foreach ($_POST as $key => $value) {
        if($key != 'course-id' && $key != 'course-date'){
            $update_attendance_query = "UPDATE attendance_table SET attendance_status = 'P' WHERE attendance_course_date_id = '$course_date' AND attendance_student_id = '$key'";
            $conn->exec($update_attendance_query);
        }
    }

    $stmt = $conn->prepare("
        SELECT
        student_table.student_id AS 'student_id',
        (SELECT COUNT(attendance_table.attendance_id) FROM attendance_table WHERE attendance_table.attendance_status='P' AND attendance_table.attendance_student_id=student_table.student_id) AS 'attendance_count',
        (SELECT COUNT(course_date_table.course_date_id) FROM course_date_table WHERE course_date_table.course_id=course_table.course_id) AS 'course_dates_count'
        FROM student_table
        JOIN course_table ON course_table.course_id=student_table.student_course_id
        ");
    $stmt->execute();
    foreach((new RecursiveArrayIterator($stmt->fetchAll())) as $key => $value) {
        if($value['attendance_count'] == $value['course_dates_count']){
            $final_attendance = 'P';
        } else{
            $final_attendance = 'A';
        }
        $update_student_query = "UPDATE student_table SET student_final_attendance = '$final_attendance' WHERE student_id = '$value[student_id]'";
        $conn->exec($update_student_query);
    }
    
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@8'></script><script type='text/javascript'>console.log('Error: ' );
        Swal.fire
            ({
            'title': 'Successful',
            'text': 'Attendance updated Successfully.',
            'type': 'success'
            })
    </script>";
    echo "<script>
            setTimeout(function() {
                let redirect = window.location.href.split('/');
                redirect = redirect.slice(0, redirect.indexOf('back-end')).join('/') + '/front-end/view_course.php?courseid=$course_id';
                window.location.href = redirect;
            }, 2000);
        </script>";

} 
catch(PDOException $e) {
    echo $e->getMessage();
}

$conn = null;

?>