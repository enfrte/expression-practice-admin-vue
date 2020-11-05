<?php 
// Get the latest file from the database folder - files are time-stamped
$dir = "database/";
$files = scandir($dir);

$files = array_filter(scandir('database/', 1), function($item) {
    return $item[0] !== '.';
});

$newest_file = $files[0];

echo $newest_file;