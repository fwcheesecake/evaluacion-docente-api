<?php
// get request parameters
header("Access-Control-Allow-Origin: *");

header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Origin, Access-Control-Allow-Headers, Authorization, X-Requested-With");

header("Content-Type: application/json; charset=UTF-8");

// include configurations
include_once '../../config/config.php';

// import class file
include_once '../../class/academy.php';

$database = new Database();
$db = $database->getConnection();

$items = new Academy($db);

$stmt = $items->addAcademies();

if($stmt) {
    http_response_code(200);
    echo json_encode(
        array("message" => "Success")
    );
} else {
    http_response_code(404);
}