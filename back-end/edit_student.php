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

    // print_r($_POST);

    // taking variables form the HTML.
    $student_present_days = array();
    foreach ($_POST as $key => $value) {
        if(is_numeric($key)){
            $student_present_days[$key] = $value;
        }
    }

    $student_id = $_POST['student-id'];
    $student_name = $_POST['student-name'];
    $student_email = $_POST['student-email'];
    $student_employee_no = $_POST['student-employee-no'];
    $student_manager_name = $_POST['student-manager-name'];
    $student_position = $_POST['student-position'];
    $student_final_attendance = $_POST['student-final-attendance'];
    $student_contact_no = $_POST['student-contact-no'];
    $student_region = $_POST['student-region'];
    
    // update query
    $student_details_query = "
    UPDATE
    `student_table`
    SET
    `student_name` = '$student_name',
    `student_email`='$student_email',
    `student_emp_no`='$student_employee_no',
    `student_manager_name`='$student_manager_name',
    `student_position`='$student_position',
    `student_final_attendance`='$student_final_attendance',
    `student_phonenumber`='$student_contact_no',
    `student_region`='$student_region'
    WHERE `student_table`.`student_id` = $student_id;";

    $conn->exec($student_details_query);

    $course_dates = json_encode(array_keys($student_present_days));
    $course_dates = str_replace("[", "(", $course_dates);
    $course_dates = str_replace("]", ")", $course_dates);

    $present_details_query = "
    UPDATE
    `attendance_table`
    SET `attendance_status` = 'P'
    WHERE `attendance_table`.`attendance_student_id` = $student_id AND `attendance_table`.`attendance_course_date_id` IN $course_dates
    ";

    $absent_details_query = "
    UPDATE
    `attendance_table`
    SET `attendance_status` = 'A'
    WHERE `attendance_table`.`attendance_student_id` = $student_id AND `attendance_table`.`attendance_course_date_id` NOT IN $course_dates
    ";
    
    $conn->exec($present_details_query);
    $conn->exec($absent_details_query);

    echo ".";
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@8'></script><script type='text/javascript'>console.log('Error: ' );
        Swal.fire
            ({
            'title': 'Successful',
            'text': 'Student Details Updated Successfully.',
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