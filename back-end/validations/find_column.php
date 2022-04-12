<?php

function find_columns($file, $column_names){
    $temp_file = $file;
    
    $data = fgetcsv($temp_file, 1000, ","); // read out the first line in file to not count the header.

    $column_indices = array();
    
    foreach ($data as $key => $value) {
        
        if ( in_array($value, $column_names) ){
            $column_indices[$value] = $key;
        }

    }

    return $column_indices;
}

?>