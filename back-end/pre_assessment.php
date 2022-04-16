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

    $filepath = 'uploads/' . basename($_FILES['pre-assessment-file']['name']);

    if (move_uploaded_file($_FILES['pre-assessment-file']['tmp_name'], $filepath)) {
        // taking variables form the HTML.
        $course_id = $_POST['course-id'];

        $file_to_be_validated = fopen($filepath, "r");
        require_once("validations/find_column.php");
        $column_indices = find_columns($file_to_be_validated, array("Email", "Total points"));

        fclose($file_to_be_validated);

        $email_column = $column_indices["Email"];
        $total_points_column = $column_indices["Total points"];

        $file_to_be_validated = fopen($filepath, "r");
        require_once("validations/duplicate_email.php");
        fclose($file_to_be_validated);

        $file_to_be_validated = fopen($filepath, "r");
        require_once("validations/file_db_email.php");
        fclose($file_to_be_validated);

        if($validated){

            $pre_assessment_file = fopen($filepath, "r");
            
            $data = fgetcsv($pre_assessment_file, 1000, ","); // read out the first line in file to not count the header.
            while (($data = fgetcsv($pre_assessment_file, 1000, ",")) !== FALSE){

                if(in_array($data[$email_column], $email_list_db)){
                    // update query to update the student details.
                    $pre_assessment_details_query = "UPDATE `student_table` SET `student_pre_assesment_score` = $data[$total_points_column] WHERE `student_table`.`student_email` = '$data[$email_column]' AND `student_table`.`student_course_id` = $course_id;";
                    $conn->exec($pre_assessment_details_query);
                }
                
            }
            fclose($pre_assessment_file);
    
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@8'></script><script type='text/javascript'>console.log('Error: ' );
                Swal.fire
                    ({
                    'title': 'Successful',
                    'text': 'Pre Assessment Report filled Successfully.',
                    'type': 'success'
                    })
            </script>";
            echo "<script>
                setTimeout(function() {
                    window.history.go(-1);
                }, 2000);
            </script>";
        }

        unlink($filepath);

    } else {
        echo "File not uploaded\n";
    }

} 
catch(PDOException $e) {
    echo $e->getMessage();
}

$conn = null;

?>