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
    // taking variables form the HTML.
    $course_id = $_POST['course-id'];

    // delete query
    $student_delete_query = "DELETE FROM student_table WHERE student_table.student_course_id = '$course_id'";
    $course_delete_query = "DELETE FROM course_table WHERE course_table.course_id = '$course_id'";

    $attendance_delete_query = "DELETE attendance_table FROM attendance_table JOIN course_date_table ON attendance_table.attendance_course_date_id = course_date_table.course_date_id WHERE course_date_table.course_id = '$course_id'";
    $course_date_delete_query = "DELETE FROM course_date_table WHERE course_date_table.course_id = '$course_id'";

    $feedback_response_delete_query = "DELETE feedback_response_table FROM feedback_response_table JOIN feedback_table ON feedback_response_table.feedback_id = feedback_table.feedback_id WHERE feedback_table.feedback_course_id = '$course_id'";
    $feedback_delete_query = "DELETE FROM feedback_table WHERE feedback_table.feedback_course_id = '$course_id'";

    $conn->exec($feedback_response_delete_query);
    $conn->exec($feedback_delete_query);
    $conn->exec($attendance_delete_query);
    $conn->exec($student_delete_query);
    $conn->exec($course_date_delete_query);
    $conn->exec($course_delete_query);

    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@8'></script><script type='text/javascript'>console.log('Error: ' );
        Swal.fire
            ({
            'title': 'Successful',
            'text': 'Course Deleted Successfully.',
            'type': 'success'
            })
    </script>";
    echo "<script>
        setTimeout(function() {
            window.history.go(-1);
        }, 1000);
    </script>";
} 
catch(PDOException $e) {
    echo $e->getMessage();
}

$conn = null;

?>