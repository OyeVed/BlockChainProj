function preAssessment(course_id, course_name) {
    Swal.fire({
        title: '<strong>Pre-Assessment Report</strong>',
        html:
            '<form action="/BlockChainProj/back-end/pre_assessment.php" method="POST" enctype="multipart/form-data">' +
            `<input type="hidden" class="form-control" name="course-id" placeholder="Course Id" value="${course_id}"/><br>` +
            `<h3>${course_name}<h3><br>` +
            'Fill Pre-Assessment Report (upload csv)<input type="file" class="form-control" name="pre-assessment-file" value="Import Students"/><br>' +
            '<input type="submit" class="form-control bg-success text-white" value="Upload" accept=".csv">' +
            '</form>',
        showCancelButton: true,
        allowEscapeKey: true,
        showConfirmButton: false,
        cancelButtonText: 'Cancel',
    });
}

function postAssessment(course_id, course_name) {
    Swal.fire({
        title: '<strong>Post-Assessment Report</strong>',
        html:
            '<form action="/BlockChainProj/back-end/post_assessment.php" method="POST" enctype="multipart/form-data">' +
            `<input type="hidden" class="form-control" name="course-id" placeholder="Course Id" value="${course_id}"/><br>` +
            `<h3>${course_name}<h3><br>` +
            'Fill Post-Assessment Report (upload csv)<input type="file" class="form-control" name="post-assessment-file" value="Import Students"/><br>' +
            '<input type="submit" class="form-control bg-success text-white" value="Upload" accept=".csv">' +
            '</form>',
        showCancelButton: true,
        allowEscapeKey: true,
        showConfirmButton: false,
        cancelButtonText: 'Cancel',
    });
}

function markAttendance(course_id, course_name) {
    Swal.fire({
        title: '<strong>Attendance Report</strong>',
        html:
            '<form action="/BlockChainProj/back-end/attendance.php" method="POST" enctype="multipart/form-data">' +
            `<input type="hidden" class="form-control" name="course-id" placeholder="Course Id" value="${course_id}"/><br>` +
            `<h3>${course_name}<h3><br>` +
            '<input type="date" class="form-control mb-3 mt-3" name="attendance-date" value="Select Date"/>' +
            'Update Attendance (upload csv)<input type="file" class="form-control" name="attendance-file" value="Import Students"/><br>' +
            '<input type="submit" class="form-control bg-success text-white" value="Upload" accept=".csv">' +
            '</form>',
        showCancelButton: true,
        allowEscapeKey: true,
        showConfirmButton: false,
        cancelButtonText: 'Cancel',
    });
}

function sendReports(course_id) {
    Swal.fire({
        title: '<strong>Email Report</strong>',
        html:
            '<b>Course Name<b><br>' +
            '<input type="text" class="form-control" placeholder="Receiver\'s mail ID\'s: "/><br>' +
            '<input type="text" class="form-control" placeholder="Email Subject: "/><br>' +
            '<textarea type="text" class="form-control" placeholder="Email Body: "/>',
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonText:
            'Send Reports',
        confirmButtonAriaLabel: 'Send Reports',
        cancelButtonText:
            'Cancel',
        cancelButtonAriaLabel: 'Cancel'
    })
}

function addCourse() {
    Swal.fire({
        title: '<strong>Add New Course</strong>',
        html:
            '<form action="/BlockChainProj/back-end/add_course.php" method="POST" enctype="multipart/form-data">' +
            '<input type="text" class="form-control" name="course-name" placeholder="Course Name"/><br>' +
            '<input type="text" class="form-control date" name="course-dates" placeholder="Pick the multiple dates"><br>' +
            'Import Students (upload csv)<input type="file" class="form-control" name="students-file" value="Import Students"/><br>' +
            '<input type="submit" class="form-control bg-success text-white" value="Add New Course" accept=".csv">' +
            '</form>',
        showCancelButton: true,
        allowEscapeKey: true,
        showConfirmButton: false,
        cancelButtonText: 'Discard',
    });
}

function editCourse(course) {

    console.log(course);

    Swal.fire({
        title: '<strong>Edit Course</strong>',
        html:
            `<form action="/BlockChainProj/back-end/edit_course.php" method="POST">` +
            `<input type="hidden" class="form-control" name="course-id" placeholder="Course Id" value="${course[0]}"/><br>` +
            `<input type="text" class="form-control" name="course-name" placeholder="Course Name" value="${course[1]}"/><br>` +
            `<input type="text" class="form-control date" name="course-dates" placeholder="Pick the multiple dates"><br>` +
            `<input type="submit" class="form-control bg-success text-white" value="Save Changes">` +
            `</form>`,
        showCancelButton: true,
        allowEscapeKey: true,
        showConfirmButton: false,
        cancelButtonText: 'Discard',
    });
}