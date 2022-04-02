-- fetch courses
SELECT
course_table.course_id AS 'id',
course_table.course_name AS 'name',
course_table.course_student_count AS 'no_of_students',
90 AS 'avg_attendance',
90 AS 'avg_pre_assessment',
90 AS 'avg_post_assessment'
FROM course_table;

-- insert course
INSERT INTO course_table
(course_name, course_student_count)
VALUES ('course_name', 45);



-- WHERE course_table.course_id = 1;