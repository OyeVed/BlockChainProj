-- https://docs.google.com/spreadsheets/d/1va-RIRffH9-CAWIusqDBtRT0O9R57eDVHVTN69m2eVA/edit#gid=0

-- courses table
CREATE TABLE IF NOT EXISTS course_table
(
    course_id INT PRIMARY KEY AUTO_INCREMENT,
    course_name VARCHAR(255) NOT NULL,
    course_trainer_id INT,
    course_student_count INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_by INT NOT NULL DEFAULT 0,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_by INT NOT NULL DEFAULT 0,
    FOREIGN KEY (course_trainer_id) REFERENCES user_tables(id)
);

-- course dates table
CREATE TABLE IF NOT EXISTS course_date_table
(
    course_date_id INT PRIMARY KEY AUTO_INCREMENT,
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
    student_final_attendance CHAR(1) DEFAULT '-' NOT NULL,
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

-- attendance table
CREATE TABLE IF NOT EXISTS attendance_table
(
    attendance_id INT PRIMARY KEY AUTO_INCREMENT,
    attendance_student_id INT NOT NULL,
    attendance_course_date_id INT NOT NULL,
    attendance_status VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_by INT NOT NULL DEFAULT 0,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_by INT NOT NULL DEFAULT 0,
    FOREIGN KEY (attendance_student_id) REFERENCES student_table(student_id),
    FOREIGN KEY (attendance_course_date_id) REFERENCES course_date_table(course_date_id)
);

-- feedback table
CREATE TABLE IF NOT EXISTS feedback_table
(
    feedback_id INT PRIMARY KEY AUTO_INCREMENT,
    feedback_course_id INT NOT NULL,
    feedback_question VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_by INT NOT NULL DEFAULT 0,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_by INT NOT NULL DEFAULT 0,
    FOREIGN KEY (feedback_course_id) REFERENCES course_table(course_id)
);

-- feedback response table
CREATE TABLE IF NOT EXISTS feedback_response_table
(
    feedback_response_id INT PRIMARY KEY AUTO_INCREMENT,
    feedback_id INT NOT NULL,
    feedback_student_id INT NOT NULL,
    feedback_response VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_by INT NOT NULL DEFAULT 0,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_by INT NOT NULL DEFAULT 0,
    FOREIGN KEY (feedback_id) REFERENCES feedback_table(feedback_id),
    FOREIGN KEY (feedback_student_id) REFERENCES student_table(student_id)
);