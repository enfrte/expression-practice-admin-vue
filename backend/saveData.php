<?php 

// Saves data to a new json database defined by a timestamp

$timestamp = time();
$filename = 'database/' . date("Ymdhis",$timestamp).'-practice-data.json';

//$data = json_decode(file_get_contents('php://input'), true);
//echo json_encode($data);
$data = file_get_contents('php://input'); // see above two lines

file_put_contents($filename, $data);

//echo '{"output": "'.gettype($data).'"}';
echo $data;

//echo json_decode($_POST);
//echo $timestamp;

//$json_example = '{"abc": 123, "def": "456"}';

//$json 
