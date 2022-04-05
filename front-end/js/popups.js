function preAssessment(course_id, course_name) {
    document.getElementById('pre_assesment_course_id').value  = course_id
    document.getElementById('pre_assesment_course_name').innerHTML = `For ${course_name}`
}

function postAssessment(course_id, course_name) {
    document.getElementById('post_assesment_course_id').value  = course_id
    document.getElementById('post_assesment_course_name').innerHTML = `For ${course_name}`
}

function markAttendance(course_id, course_name) {
    document.getElementById('mark_attendance_course_name').innerHTML = `For ${course_name}`
    document.getElementById('mark_attendance_course_id').value  = course_id
}

function sendReports(course_id) {
    Swal.fire({
        title: '',
        html:
            '<h5 style="text-align:left;" >Send Email Report</h5><hr />' +
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

function editCourse(course) {
    let course_id = document.getElementById('edit_course_course_id')
    course_id.value = course[0]
  let course_name = document.getElementById('edit_course_course_name')
  course_name.value = course[1]
}

function deleteCourse(course_id, course_name) {
    Swal.fire({
        title: 'Are you sure?',
        icon: 'warning',
        type: 'warning',
                html:
            '<form action="/BlockChainProj/back-end/delete_course.php" method="POST" enctype="multipart/form-data">' +
            `<small>You won't be able to revert ${course_name}!</small>` +
            `<input type="hidden" class="form-control" name="course-id" placeholder="Course Id" value="${course_id}"/><br>` +
            `<input  id="delete_course_btn" type="submit" style="width: 120px; visibility: hidden;" class="form-control bg-danger text-white" value="Confirm Delete">` +
            '</form>',
        showCancelButton: true,
        showConfirmButton: true,
        allowEscapeKey: true,
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result?.value) {
            let dltBtn = document.getElementById('delete_course_btn')
            console.log(dltBtn)
            dltBtn.click()
        }
      })
}