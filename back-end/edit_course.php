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
    $course_name = $_POST['course-name'];
    $course_dates = $_POST['course-dates'];

    // insert query
    $course_details_query = "UPDATE `course_table` SET `course_name` = '$course_name' WHERE `course_table`.`course_id` = $course_id;";

    $conn->exec($course_details_query);

    echo ".";
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@8'></script><script type='text/javascript'>console.log('Error: ' );
        Swal.fire
            ({
            'title': 'Successful',
            'text': 'Course Updated Successfully.',
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
    echo $sql . "<br>" . $e->getMessage();
}


$conn = null;

?>