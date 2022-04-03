INSERT INTO course_date_table
(
    course_id,
    course_date
)
VALUES
(1, '2022-04-02'),
(1, '2022-04-03'),
(1, '2022-04-04');

INSERT INTO attendance_table
(
    attendance_student_id,
    attendance_course_date_id,
    attendance_status
)
VALUES
(1, 1, 'P'),
(2, 1, 'A'),
(3, 1, 'P'),
(1, 2, 'A'),
(2, 2, '-'),
(3, 2, 'P'),
(1, 3, 'P'),
(2, 3, 'P'),
(3, 3, '-');