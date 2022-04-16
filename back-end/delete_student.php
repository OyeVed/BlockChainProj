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
    $student_id = $_POST['student-id'];
    $course_id = $_POST['course-id'];

    // update query
    $course_update_query = "UPDATE course_table SET course_student_count = course_student_count - 1 WHERE course_id=$course_id";

    // delete query
    $student_delete_query = "DELETE student_table FROM student_table WHERE student_table.student_id = '$student_id'";

    $attendance_delete_query = "DELETE attendance_table FROM attendance_table WHERE attendance_student_id = '$student_id'";

    $feedback_response_delete_query = "DELETE feedback_response_table FROM feedback_response_table WHERE feedback_response_table.feedback_student_id = '$student_id'";

    $conn->exec($feedback_response_delete_query);
    $conn->exec($attendance_delete_query);
    $conn->exec($student_delete_query);
    $conn->exec($course_update_query);

    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@8'></script><script type='text/javascript'>console.log('Error: ' );
        Swal.fire
            ({
            'title': 'Successful',
            'text': 'Student Deleted Successfully.',
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