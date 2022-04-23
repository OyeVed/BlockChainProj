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

    // echo "<br>";
    // echo "<br>";
    
    // print_r($_FILES);
    
    // die();
    
    $filepath = 'uploads/' . basename($_FILES['feedback-file']['name']);

    if (move_uploaded_file($_FILES['feedback-file']['tmp_name'], $filepath)) {
        // taking variables form the HTML.
        $course_id = $_POST['course-id'];

        $file_to_be_validated = fopen($filepath, "r");
        require_once("validations/find_column.php");
        $column_indices = find_columns($file_to_be_validated, array("Email", "Participant Name"));
        fclose($file_to_be_validated);
        
        $email_column = $column_indices["Email"];
        $start_feedback_questions_column = $column_indices["Participant Name"] + 1;

        $file_to_be_validated = fopen($filepath, "r");
        require_once("validations/duplicate_email.php");
        fclose($file_to_be_validated);

        $file_to_be_validated = fopen($filepath, "r");
        require_once("validations/file_db_email.php");
        fclose($file_to_be_validated);
        
        if($validated){

            $feedback_file = fopen($filepath, "r");
            $data = fgetcsv($feedback_file, 1000, ","); // read out the first line in file to not count the header.
            
            $feedback_questions = array();
            
            // insert query to insert the attendance details.
            $feedback_details_query = "INSERT INTO feedback_table (feedback_id, feedback_course_id, feedback_question) VALUES";
            for ($i = $start_feedback_questions_column; $i < sizeof($data); $i++) { 

                $sql = "SELECT MAX(feedback_table.feedback_id) AS feedback_id FROM feedback_table";
                $query = $conn -> prepare($sql);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                $feedback_id = $results[0]->feedback_id;

                if($feedback_id == null){
                    $feedback_id = 1;
                }
                else{
                    $feedback_id = $feedback_id + 1;
                }
                
                $conn->exec($feedback_details_query . " ($feedback_id, $course_id, '$data[$i]')");

                array_push($feedback_questions, array($feedback_id, $data[$i]));
            }

            while (($data = fgetcsv($feedback_file, 1000, ",")) !== FALSE){

                $sql = "SELECT student_table.student_id AS student_id FROM student_table WHERE student_table.student_email = '$data[$email_column]'";
                $query = $conn -> prepare($sql);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                $student_id = $results[0]->student_id;

                if($student_id != null){

                    $question_index = 0;
                    
                    $feedback_details_query = "INSERT INTO feedback_response_table (feedback_id, feedback_student_id, feedback_response) VALUES";
                    
                    for ($i = $start_feedback_questions_column; $i < sizeof($data); $i++) { 

                        $question_id = $feedback_questions[$question_index][0];
                        
                        $feedback_details_query .= " ($question_id, $student_id, '$data[$i]'),";
                        
                        $question_index++;
                        
                    }
                    $feedback_details_query = rtrim($feedback_details_query, ",");

                }

                $conn->exec($feedback_details_query);
                
            }
            fclose($feedback_file);

            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@8'></script><script type='text/javascript'>console.log('Error: ' );
                Swal.fire
                    ({
                    'title': 'Successful',
                    'text': 'Feedback Report filled Successfully.',
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