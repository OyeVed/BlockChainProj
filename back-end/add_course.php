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
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;

$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();


try{
    
    if(!file_exists('uploads/')){
        mkdir('uploads/');
    }
    
    $filepath = 'uploads/' . basename($_FILES['students-file']['name']);

    if (move_uploaded_file($_FILES['students-file']['tmp_name'], $filepath)) {
        // taking variables form the HTML.
        $course_name = $_POST['course-name'];
        $course_dates = $_POST['course-dates'];
        $course_trainer = $_POST['course-trainer'];

        $students_file = fopen($filepath, "r");

        $sql = "SELECT MAX(course_table.course_id) AS course_id FROM course_table";
        $query = $conn -> prepare($sql);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        $course_id = $results[0]->course_id;

        if($course_id == null){
            $course_id = 1;
        }
        else{
            $course_id = $course_id + 1;
        }

        $sql = "SELECT MAX(course_date_table.course_date_id) AS course_date_id FROM course_date_table";
        $query = $conn -> prepare($sql);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        $course_date_id = $results[0]->course_date_id;

        if($course_date_id == null){
            $course_date_id = 1;
        }
        else{
            $course_date_id = $course_date_id + 1;
        }

        $sql = "SELECT MAX(student_table.student_id) AS student_id FROM student_table";
        $query = $conn -> prepare($sql);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        $student_id = $results[0]->student_id;

        if($student_id == null){
            $student_id = 1;
        }
        else{
            $student_id = $student_id + 1;
        }

        // insert query to insert the student details.
        $student_details_query = "INSERT INTO student_table (student_id, student_course_id, student_name, student_emp_no, student_phonenumber, student_email, student_division, student_region, student_position, student_manager_name) VALUES";
        $no_of_students = 0;
        $new_student_id = $student_id;

        $spreadsheet = $reader->load($filepath);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        unset($sheetData[0]);

        foreach ($sheetData as $data) {
        // process element here;
                if(
                    $data[0] != ''
                    && $data[1] != ''
                    && $data[2] != ''
                    && $data[3] != ''
                    && $data[4] != ''
                    && $data[5] != ''
                    && $data[6] != ''
                    && $data[7] != ''
                ){
                    $student_details_query .= " ('$new_student_id', '$course_id', '$data[0]', '$data[1]', '$data[2]', '$data[3]', '$data[4]', '$data[5]', '$data[6]', '$data[7]'),";
                    $no_of_students++;
                    $new_student_id++;
                }
            // echo '<pre>';
            // print_r($t);
        }
        // $data = fgetcsv($students_file, 1000, ","); // read out the first line in file to not count the header.
        // while (($data = fgetcsv($students_file, 1000, ",")) !== FALSE){
        //     if(
        //         $data[0] != ''
        //         && $data[1] != ''
        //         && $data[2] != ''
        //         && $data[3] != ''
        //         && $data[4] != ''
        //         && $data[5] != ''
        //         && $data[6] != ''
        //         && $data[7] != ''
        //     ){
        //         $student_details_query .= " ('$new_student_id', '$course_id', '$data[0]', '$data[1]', '$data[2]', '$data[3]', '$data[4]', '$data[5]', '$data[6]', '$data[7]'),";
        //         $no_of_students++;
        //         $new_student_id++;
        //     }
        // }
        fclose($students_file);
        $student_details_query = rtrim($student_details_query, ",");

        // insert query to insert the course dates details.
        $course_date_details_query = "INSERT INTO course_date_table (course_date_id, course_id, course_date) VALUES";
        $no_of_course_dates = 0;
        $new_course_date_id = $course_date_id;
        foreach (explode(',', $course_dates) as $value) {
            $date = explode('/', $value);
            $course_date_details_query .= " ('$new_course_date_id', '$course_id', '$date[2]-$date[1]-$date[0]'),";
            $no_of_course_dates++;
            $new_course_date_id++;
        }
        $course_date_details_query = rtrim($course_date_details_query, ",");

        // insert query to insert the attendance details.
        $attendance_details_query = "INSERT INTO attendance_table (attendance_student_id, attendance_course_date_id, attendance_status) VALUES";
        for($i = $student_id; $i < $student_id + $no_of_students; $i++){
            for($j = $course_date_id; $j < $course_date_id + $no_of_course_dates; $j++){
                $attendance_details_query .= " ('$i', '$j', '-'),";
            }
        }
        $attendance_details_query = rtrim($attendance_details_query, ",");

        // insert query to insert the course details.
        $batch_code = $_POST['course-batch-code'];
        $training_code = $_POST['course-training-code'];

        $course_details_query = "INSERT INTO course_table (course_id, course_name, course_trainer_id, course_student_count, course_batch_code, course_training_code) VALUES ($course_id, '$course_name', $course_trainer, $no_of_students, '$batch_code', '$training_code')";
        
        // echo $course_details_query;

        // echo "<br>";
        // echo "<br>";

        // echo $course_date_details_query;

        // echo "<br>";
        // echo "<br>";

        // echo $student_details_query;
        
        // echo "<br>";
        // echo "<br>";

        // echo $attendance_details_query;
        
        $conn->exec($course_details_query);
        $conn->exec($course_date_details_query);
        $conn->exec($student_details_query);
        $conn->exec($attendance_details_query);

        unlink($filepath);

        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@8'></script><script type='text/javascript'>console.log('Error: ' );
            Swal.fire
                ({
                'title': 'Successful',
                'text': 'New Course added Successfully.',
                'type': 'success'
                })
        </script>";
        echo "<script>
                setTimeout(function() {
                    let redirect = window.location.href.split('/');
                    redirect = redirect.slice(0, redirect.indexOf('back-end')).join('/') + '/front-end/view_courses.php';
                    window.location.href = redirect;
                }, 2000);
            </script>";

    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@8'></script><script type='text/javascript'>console.log('Error: ' );
                Swal.fire(
                    'Error!',
                    'New Course addition unsuccessful.',
                    'error'
                  )
        </script>";
        echo "<script>
                setTimeout(function() {
                    let redirect = window.location.href.split('/');
                    redirect = redirect.slice(0, redirect.indexOf('back-end')).join('/') + '/front-end/view_courses.php';
                    window.location.href = redirect;
                }, 2000);
            </script>";
    }

} 
catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

$conn = null;

?>