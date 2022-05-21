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
include_once '../../class/academy.php';

$database = new Database();
$db = $database -> getConnection();

$items = new Academy($db);

$stmt = $items -> getAllAcademies();
$itemCount = $stmt -> rowCount();

echo json_encode($itemCount);

if($itemCount > 0) {
    $academiesArray = array();
    $academiesArray["body"] = array();
    $academiesArray["itemCount"] = $itemCount;

    while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $resultData = array(
            "clave" => $clave,
            "descripcion" => $descripcion,
            "tipo" => $tipo
        );
        $academiesArray["body"][] = $resultData;
    }
    http_response_code(200);
    echo json_encode($academiesArray);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No record found.")
    );
}
