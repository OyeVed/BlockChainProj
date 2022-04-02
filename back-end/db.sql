-- https://docs.google.com/spreadsheets/d/1va-RIRffH9-CAWIusqDBtRT0O9R57eDVHVTN69m2eVA/edit#gid=0

-- courses table
CREATE TABLE IF NOT EXISTS course_table
(
    course_id INT PRIMARY KEY AUTO_INCREMENT,
    course_name VARCHAR(255) NOT NULL,
    course_student_count INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_by INT NOT NULL DEFAULT 0,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_by INT NOT NUL DEFAULT 0L
);

CREATE TABLE IF NOT EXISTS course_date_table
(
    course_date_id INT PRIMARY KEY AUTOINCREMENT,
    course_id INT NOT NULL,
    course_date DATE NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_by INT NOT NULL DEFAULT 0,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_by INT NOT NULL DEFAULT 0,
    FOREIGN KEY (course_id) REFERENCES course_table(course_id)
);

-- students table
CREATE TABLE IF NOT EXISTS student_table
(
    student_id INT PRIMARY KEY AUTO_INCREMENT,
    student_course_id INT NOT NULL,
    student_name VARCHAR(255) NOT NULL,
    student_emp_no INT NOT NULL,
    student_phonenumber INT NOT NULL,
    student_email VARCHAR(255) NOT NULL,
    student_division VARCHAR(255) NOT NULL,
    student_position VARCHAR(255) NOT NULL,
    student_manager_name VARCHAR(255) NOT NULL,
    student_region VARCHAR(255) NOT NULL,
    student_pre_assesment_score INT NOT NULL,
    student_post_assesment_score INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_by INT NOT NULL DEFAULT 0,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_by INT NOT NULL DEFAULT 0,
    FOREIGN KEY (student_course_id) REFERENCES course_table(course_id)
);