<?php
// get request parameters
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: POST, PUT, DELETE, UPDATE");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include configurations
include_once '../../config/config.php';

// import class file
include_once '../../class/career.php';

$database = new Database();
$db = $database -> getConnection();

$items = new Career($db);

$stmt = $items->addCareer();

if($stmt) {
    http_response_code(200);
    echo json_encode(
        array("message" => "Success.")
    );
} else {
    http_response_code(404);
}