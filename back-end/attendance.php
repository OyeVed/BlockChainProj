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

    $filepath = 'uploads/' . basename($_FILES['attendance-file']['name']);

    if (move_uploaded_file($_FILES['attendance-file']['tmp_name'], $filepath)) {
        // taking variables form the HTML.
        $course_id = $_POST['course-id'];
        $attendance_date = $_POST['attendance-date'];

        print_r($course_id);
        
        $attendance_file = fopen($filepath, "r");

        $data = fgetcsv($attendance_file, 1000, ","); // read out the first line in file to not count the header.
        while (($data = fgetcsv($attendance_file, 1000, ",")) !== FALSE){

            // update query to update the student details.
            // $attendance_file_details_query = "UPDATE `student_table` SET `student_pre_assessment_score` = $data[] WHERE `student_table`.`student_email` = $data[] AND `student_table`.`student_course_id` = $course_id;";

            // $conn->exec($attendance_file_details_query);
            
        }
        fclose($attendance_file);

        unlink($filepath);

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

    } else {
        echo "File not uploaded\n";
    }

} 
catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

$conn = null;

?>