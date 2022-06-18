<?php

// require_once 'vendor/autoload.php';

// use PhpOffice\PhpSpreadsheet\Spreadsheet;

// $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

function find_columns($sheetData, $column_names){

    $column_indices = array();
    
    // $spreadsheet = $reader->load($filename);
	// $sheetData = $spreadsheet->getActiveSheet()->toArray();

	foreach ($sheetData as $array) {
		foreach($array as $key => $value){
			if ( in_array($value, $column_names) ){
				$column_indices[$value] = $key;
			}
		}
	}

    return $column_indices;
}

?>