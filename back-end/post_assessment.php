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
require_once("common/connection.php");
require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;

$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();


try{

    $filepath = 'uploads/' . basename($_FILES['post-assessment-file']['name']);

    if (move_uploaded_file($_FILES['post-assessment-file']['tmp_name'], $filepath)) {
        // taking variables form the HTML.
        $course_id = $_POST['course-id'];

        $spreadsheet = $reader->load($filepath);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        require_once("validations/find_column.php");
        $column_indices = find_columns($sheetData, array("Email", "Total points"));
        
        $email_column = $column_indices["Email"];
        $total_points_column = $column_indices["Total points"];

        require_once("validations/duplicate_email.php");

        require_once("validations/file_db_email.php");

        if($validated){

            $header = 0;
            foreach ($sheetData as $data) {
                if(in_array($data[$email_column], $email_list_db) && $header>0){
                    // update query to update the student details.
                    $post_assessment_details_query = "UPDATE `student_table` SET `student_post_assesment_score` = $data[$total_points_column] WHERE `student_table`.`student_email` = '$data[$email_column]' AND `student_table`.`student_course_id` = $course_id;";
                    $conn->exec($post_assessment_details_query);
                }
                $header++;
                
            }
    
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@8'></script><script type='text/javascript'>console.log('Error: ' );
                Swal.fire
                    ({
                    'title': 'Successful',
                    'text': 'Post Assessment Report filled Successfully.',
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