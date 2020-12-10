<?php 
/**
 * Construct classes from routes and return stuff
 */

// var_dump($_GET);
require __DIR__ . DIRECTORY_SEPARATOR . $_GET['class'] . '.php';

$class = $_GET['class'];
$method = $_GET['method'];

// Construct the class
$new_class = new $class;
$result = $new_class->$method();

echo($result);