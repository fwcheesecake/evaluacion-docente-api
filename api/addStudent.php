<?php
// get request parameters
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: POST, PUT, DELETE, UPDATE");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include configurations
include_once '../config/config.php';

// import class file
include_once '../class/students.php';

$database = new Database();
$db = $database -> getConnection();

$items = new Students($db);

$stmt = $items->addStudent();
?>