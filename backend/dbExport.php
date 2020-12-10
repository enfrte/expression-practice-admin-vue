<?php 
/**
 * Creates datasets modules, questions, and tutorials from main dataset, and saves them to individual files.
 */
require __DIR__ . DIRECTORY_SEPARATOR . 'latestDatabase.php';

$getLatestDatabase = new latestDatabase();
$dbFilename = $getLatestDatabase->getDb(); 

$dbFileContents = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . "database" . DIRECTORY_SEPARATOR . $dbFilename);
$dbArr = json_decode($dbFileContents, true);

// Process modules (lessons)
$module_arr = [];
// Populate module data array
foreach ($dbArr as $key => $value) {
  $module_arr[] = [
    '_id' => $value['_id'],
    'title' => $value['title'],
  ];
}
//var_dump($module_arr);
$modules_data = json_encode($module_arr);
//echo $modules_data;

// Process questions
$questions_arr = [];
// Create questions' objects by id
foreach ($dbArr as $key => $value) {
  $questions_arr[$value['_id']] = [];
}
// Populate question's objects by questions array
foreach ($questions_arr as $qa_key => $qa_value) {
  foreach ($dbArr as $db_key => $db_value) {
    if ($qa_key === $db_value['_id']) {
      $questions_arr[$qa_key] = $db_value['questions'];
    }
  }
}

// Process questions
$tutorials_arr = [];
// Create questions' objects by id
foreach ($dbArr as $key => $value) {
  $tutorials_arr[$value['_id']] = [];
}
// Populate question's objects by questions array
foreach ($tutorials_arr as $ta_key => $ta_value) {
  foreach ($dbArr as $db_key => $db_value) {
    if ($ta_key === $db_value['_id']) {
      $tutorials_arr[$ta_key]['title'] = $db_value['title'];
      $tutorials_arr[$ta_key]['tutorial'] = $db_value['tutorial'];
    }
  }
}

echo '<pre>';
//var_dump($tutorials_arr);
//$tutorials_arr = json_encode($tutorials_arr);
//echo $tutorials_arr;

// TODO: Save this to files

$timestamp = date("Ymdhis",time());

$files = [
  'questionsUrl' => 'questions-data.json?v='.$timestamp,
  'modulesUrl' => 'modules-data.json?v='.$timestamp,
  'tutorialsUrl' => 'tutorials-data.json?v='.$timestamp,
];

