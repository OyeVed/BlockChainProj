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

    $filepath = 'uploads/' . basename($_FILES['students-file']['name']);

    if (move_uploaded_file($_FILES['students-file']['tmp_name'], $filepath)) {
        // taking variables form the HTML.
        $course_name = $_POST['course-name'];
        $course_dates = $_POST['course-dates'];
        $no_of_students = 0;

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

        // insert query to insert the student details.
        $student_details_query = "INSERT INTO student_table (student_course_id, student_name, student_emp_no, student_phonenumber, student_email, student_division, student_region, student_position, student_manager_name) VALUES";
        
        $data = fgetcsv($students_file, 1000, ","); // read out the first line in file to not count the header.
        while (($data = fgetcsv($students_file, 1000, ",")) !== FALSE){
            $student_details_query .= " ('$course_id', '$data[0]', '$data[1]', '$data[2]', '$data[3]', '$data[4]', '$data[5]', '$data[6]', '$data[7]'),";
            $no_of_students++;
        }
        fclose($students_file);
        $student_details_query = rtrim($student_details_query, ",");
        
        // insert query to insert the course details.
        $course_details_query = "INSERT INTO course_table (course_id, course_name, course_student_count) VALUES ($course_id, '$course_name', $no_of_students)";

        $conn->exec($course_details_query);
        $conn->exec($student_details_query);

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
                window.history.go(-1);
            }, 1000);
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