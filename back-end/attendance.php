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
            window.history.go(-1);
        }, 2000);
    </script>";

} 
catch(PDOException $e) {
    echo $e->getMessage();
}

$conn = null;

?>