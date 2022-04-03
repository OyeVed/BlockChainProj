<?php

$email_list = array();

$entry_no = 0;

$validated = TRUE;

$data = fgetcsv($file_to_be_validated, 1000, ","); // read out the first line in file to not count the header.
while (($data = fgetcsv($file_to_be_validated, 1000, ",")) !== FALSE){

    $entry_no++;
    if(!in_array($data[$email_column], $email_list)){
        array_push($email_list, $data[$email_column]);
    }
    else{

        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@8'></script><script type='text/javascript'>console.log('Error: ' );
            Swal.fire
                ({
                'title': 'Error',
                'text': 'Duplicate Email Found : $data[$email_column] at entry no $entry_no',
                'type': 'error'
                })
        </script>";
        echo "<script>
            setTimeout(function() {
                window.history.go(-1);
            }, 2000);
        </script>";

        $validated = FALSE;
        
    }
    
}


?>