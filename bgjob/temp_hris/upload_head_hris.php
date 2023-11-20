<?php

class KeyValuePair
	{
		public $Key;
		public $Value;
	}

function compare($first, $second) {
	return strcmp($first->Value, $second->Value);
}

function GetShiftIndexes($key)
{
	$keyLength = strlen($key);
	$indexes = array();
	$sortedKey = array();
	$i;

	for ($i = 0; $i < $keyLength; ++$i) {
		$pair = new KeyValuePair();
		$pair->Key = $i;
		$pair->Value = $key[$i];
		$sortedKey[] = $pair;
	}

	usort($sortedKey, 'compare');
	$i = 0;

	for ($i = 0; $i < $keyLength; ++$i)
		$indexes[$sortedKey[$i]->Key] = $i;

	return $indexes;
}

function encrypt($str) {
	$offset = chr(53);
    $encrypted_text = "";
    $offset = $offset % 26;
    if($offset < 0) {
        $offset += 26;
    }
    
    $offset2 = $offset % 10;
    if($offset2 < 0) {
        $offset2 += 10;
    }
    
    $i = 0;
    while($i < strlen($str)) {
        $c = $str[$i]; 
        if(($c >= "A") && ($c <= 'Z')) {
            if((ord($c) + $offset) > ord("Z")) {
                $encrypted_text .= chr(ord($c) + $offset - 26);
            } else {
                $encrypted_text .= chr(ord($c) + $offset);
            }
      } elseif(($c >= "a") && ($c <= 'z')) {
            if((ord($c) + $offset) > ord("z")) {
                $encrypted_text .= chr(ord($c) + $offset - 26);
            } else {
                $encrypted_text .= chr(ord($c) + $offset);
            }
      } elseif(($c >= "0") && ($c <= "9")) {
				if((ord($c) + $offset2) > ord("9")) {
					$encrypted_text .= chr(ord($c) + $offset2 - 10);
			} else {
				$encrypted_text .= chr(ord($c) + $offset2);
			}
	  } else {
            $encrypted_text .= $c;
      }
      $i++;
    }
    
	
	$padChar = "#";
	$input 	= $encrypted_text;
	$key 	= "ibswhris";
	$output = "";
	$totalChars = strlen($input);
	$keyLength = strlen($key);
	$input = ($totalChars % $keyLength == 0) ? $input : str_pad($input, $totalChars - ($totalChars % $keyLength) + $keyLength, $padChar, STR_PAD_RIGHT);
	$totalChars = strlen($input);
	$totalColumns = $keyLength;
	$totalRows = ceil($totalChars / $totalColumns);
	$rowChars = array(array());
	$colChars = array(array());
	$sortedColChars = array(array());
	$currentRow = 0; $currentColumn = 0; $i = 0; $j = 0;
	$shiftIndexes = GetShiftIndexes($key);

	for ($i = 0; $i < $totalChars; ++$i)
	{
		$currentRow = $i / $totalColumns;
		$currentColumn = $i % $totalColumns;
		$rowChars[$currentRow][$currentColumn] = $input[$i];
	}

	for ($i = 0; $i < $totalRows; ++$i)
		for ($j = 0; $j < $totalColumns; ++$j)
			$colChars[$j][$i] = $rowChars[$i][$j];

	for ($i = 0; $i < $totalColumns; ++$i)
		for ($j = 0; $j < $totalRows; ++$j)
			$sortedColChars[$shiftIndexes[$i]][$j] = $colChars[$i][$j];

	for ($i = 0; $i < $totalChars; ++$i)
	{
		$currentRow = $i / $totalRows;
		$currentColumn = $i % $totalRows;
		$output .= $sortedColChars[$currentRow][$currentColumn];
	}
	
	
	$offset = chr(51);
    $encrypted = "";
    $offset = $offset % 26;
    if($offset < 0) {
        $offset += 26;
    }
    
    $offset2 = $offset % 10;
    if($offset2 < 0) {
        $offset2 += 10;
    }
    
    $i = 0;
    while($i < strlen($output)) {
        $c = $output[$i]; 
        if(($c >= "A") && ($c <= 'Z')) {
            if((ord($c) + $offset) > ord("Z")) {
                $encrypted .= chr(ord($c) + $offset - 26);
            } else {
                $encrypted .= chr(ord($c) + $offset);
            }
      } elseif(($c >= "a") && ($c <= 'z')) {
            if((ord($c) + $offset) > ord("z")) {
                $encrypted .= chr(ord($c) + $offset - 26);
            } else {
                $encrypted .= chr(ord($c) + $offset);
            }
      } elseif(($c >= "0") && ($c <= "9")) {
				if((ord($c) + $offset2) > ord("9")) {
					$encrypted .= chr(ord($c) + $offset2 - 10);
			} else {
				$encrypted .= chr(ord($c) + $offset2);
			}
	  } else {
            $encrypted .= $c;
      }
      $i++;
    }
	
	return $encrypted;
}

    ////////////////////////////////////////////////////////$$$//////////////////////////////////////////////////


$localhost = "localhost"; 
$username = "cikini"; 
$password = "@C1k1n1#2022"; 
$dbname = "db_hris"; 
    
// create connection 
$conn = new mysqli($localhost, $username, $password, $dbname); 
    
// check connection 
if($conn->connect_error) {
    die("connection failed : " . $conn->connect_error);
} else {
    echo "Successfully Connected<br>";
}


$file = "Form_header_history.csv";

if(file_exists("$file")){
	echo "File Template Head MDCR tersedia";
    echo "<br><br>";
    //die;

    $handle1= fopen("$file","r"); 
    $flag = true;
    while(($data=fgetcsv($handle1,0,';'))!== FALSE){ 

            if($flag) { $flag = false; continue; }
            $complete_name                  = encrypt($data[2]);
            $is_status                      = 3;
            $created_by                     = $data[4];
            $created_at                     = '2022-12-31';
            $request_id                     = $data[6];
            $employee_group                 = encrypt($data[7]);
            $phone_number                   = encrypt($data[8]);
            $department                     = encrypt($data[9]);
            $personnel_area                 = encrypt($data[10]);
            $employee_id                    = $data[11];
            $id_eg_prj                      = encrypt($data[12]);
            $id_eg_pri                      = encrypt($data[13]);
            $id_eg_pk                       = encrypt($data[14]);
            $marital_status                 = encrypt($data[15]);
            $gender                         = encrypt($data[16]);
            $id_hr_emp                      = encrypt($data[17]);

            //var_dump($data);

            // if($data[6] == '284'){

            
            $sql = "INSERT INTO hris_medical_reimbursment (complete_name, is_status, created_by, created_at, request_id, employee_group, phone_number, department, personnel_area, employee_id, id_eg_prj, id_eg_pri, id_eg_pk, marital_status, gender, id_hr_emp) VALUES ('$complete_name', '$is_status', '$created_by', '$created_at', '$request_id', '$employee_group', '$phone_number', '$department', '$personnel_area', '$employee_id', '$id_eg_prj', '$id_eg_pri', '$id_eg_pk', '$marital_status', '$gender', '$id_hr_emp')";
            //var_dump($sql);
            $stmt = $conn->query($sql);
            //var_dump($stmt);
            if( $stmt === false ) {
                die( print_r( mysqli_errors(), true));
            } 

            // }
        }
          
}else{
	echo "File yang di cari tidak ada !";
    die;
}
?>
