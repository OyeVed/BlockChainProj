<?php

$stmt = $conn->prepare("
          SELECT
          student_table.student_email AS email
          FROM student_table
          WHERE student_table.student_course_id = $course_id
          ");
$stmt->execute();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

$email_list_db = array();
          
foreach((new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
    array_push($email_list_db, $v['email']);
}

$email_list = array();

$spreadsheet = $reader->load($filepath);
$sheetData = $spreadsheet->getActiveSheet()->toArray();

foreach ($sheetData as $data) {
    array_push($email_list, $data[$email_column]);
}

// foreach ($sheetData as $data) {
//     array_push($email_list, $data[$email_column]);
// }

$emails_in_sheet_but_not_in_db = array_diff($email_list, $email_list_db);
$emails_in_db_but_not_in_sheet = array_diff($email_list_db, $email_list);

// echo "Emails in Sheet But Not In Db: ";
// echo "<br>";
// print_r($emails_in_sheet_but_not_in_db);

// echo "<br>";
// echo "<br>";
// echo "Emails in Db But Not In Sheet: ";
// echo "<br>";
// print_r($emails_in_db_but_not_in_sheet);

?>