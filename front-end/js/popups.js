function preAssessment(course_id, course_name) {
    Swal.fire({
        title: '<strong>Upload Pre-Assessment Report</strong>',
        html:
            '<form action="/BlockChainProj/back-end/pre_assessment.php" method="POST" enctype="multipart/form-data">' +
            `<input type="hidden" class="form-control" name="course-id" placeholder="Course Id" value="${course_id}"/><br>` +
            `<h3>${course_name}<h3><br>` +
            '<input type="file" class="form-control" accept=".csv" name="pre-assessment-file" value="Import Students"/><br>' +
            '<center><center><input type= style="width: 120px;""submit" style="width: 120px;" class="form-control btn-success text-white" value="Upload"></center></center>' +
            '</form>',
        showCancelButton: true,
        allowEscapeKey: true,
        showConfirmButton: false,
        cancelButtonText: 'Cancel',
    });
}

function postAssessment(course_id, course_name) {
    Swal.fire({
        title: '<strong>Upload Post-Assessment Report</strong>',
        html:
            '<form action="/BlockChainProj/back-end/post_assessment.php" method="POST" enctype="multipart/form-data">' +
            `<input type="hidden" class="form-control" name="course-id" placeholder="Course Id" value="${course_id}"/><br>` +
            `<h3>${course_name}<h3><br>` +
            '<input type="file" class="form-control" accept=".csv" name="post-assessment-file" value="Import Students"/><br>' +
            '<center><center><input type= style="width: 120px;""submit" style="width: 120px;" class="form-control btn-success text-white" value="Upload"></center></center>' +
            '</form>',
        showCancelButton: true,
        allowEscapeKey: true,
        showConfirmButton: false,
        cancelButtonText: 'Cancel',
    });
}

function markAttendance(course_id, course_name) {
    Swal.fire({
        title: '<strong>Mark Attendance</strong>',
        html:
            '<form action="/BlockChainProj/back-end/attendance.php" method="POST" enctype="multipart/form-data">' +
            `<input type="hidden" class="form-control" name="course-id" placeholder="Course Id" value="${course_id}"/><br>` +
            `<h3>${course_name}<h3>` +
            '<input type="date" class="form-control mb-3 mt-3" name="attendance-date" value="Select Date"/>' +
            '<input type="file" class="form-control" name="attendance-file" value="Import Students" accept=".csv"/><br>' +
            '<center><input type="submit" style="width: 120px;" class="form-control btn-success text-white" value="Upload"></center>' +
            '</form>',
        showCancelButton: true,
        allowEscapeKey: true,
        showConfirmButton: false,
        cancelButtonText: 'Cancel',
    });
}

function sendReports(course_id) {
    Swal.fire({
        title: '<strong>Send Email Report</strong>',
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
            'Import Students (upload csv)<input type="file" class="form-control" name="students-file" accept=".csv" value="Import Students"/><br>' +
            '<center><input type="submit" style="width: 120px;" class="form-control btn-success text-white" value="Add New Course"></center>' +
            '</form>',
        showCancelButton: true,
        allowEscapeKey: true,
        showConfirmButton: false,
        cancelButtonText: 'Discard',
    });
}

function editCourse(course) {

    Swal.fire({
        title: '<strong>Edit Course</strong>',
        html:
            `<form action="/BlockChainProj/back-end/edit_course.php" method="POST">` +
            `<input type="hidden" class="form-control" name="course-id" placeholder="Course Id" value="${course[0]}"/><br>` +
            `<input type="text" class="form-control" name="course-name" placeholder="Course Name" value="${course[1]}"/><br>` +
            `<input type="text" class="form-control date" name="course-dates" placeholder="Pick the multiple dates"><br>` +
            `<center><input type="submit" style="width: 120px;" class="form-control btn-success text-white" value="Save Changes"></center>` +
            `</form>`,
        showCancelButton: true,
        allowEscapeKey: true,
        showConfirmButton: false,
        cancelButtonText: 'Discard',
    });
}

function deleteCourse(course_id, course_name) {
    Swal.fire({
        title: '<strong>Delete Course?</strong>',
        html:
            '<form action="/BlockChainProj/back-end/delete_course.php" method="POST" enctype="multipart/form-data">' +
            `<input type="hidden" class="form-control" name="course-id" placeholder="Course Id" value="${course_id}"/><br>` +
            `<h3>${course_name}<h3><br>` +
            `<center><input type="submit" style="width: 120px;" class="form-control bg-danger text-white" value="Confirm Delete"></center>` +
            '</form>',
        showCancelButton: true,
        allowEscapeKey: true,
        showConfirmButton: false,
        cancelButtonText: 'Cancel',
    });
}