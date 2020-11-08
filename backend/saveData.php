<?php 
// Saves data to a new json object database - defined by a timestamp

$timestamp = time();
$filename = 'database/' . date("Ymdhis",$timestamp).'-practice-data.json';

//$data = json_decode(file_get_contents('php://input'), true);
//echo json_encode($data);
$data = file_get_contents('php://input'); 

file_put_contents($filename, $data);

echo $data; // or perhaps return?