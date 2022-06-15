<?php
// get request parameters
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: POST, PUT, DELETE, UPDATE");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$method = $_SERVER['REQUEST_METHOD'];
if($method == "OPTIONS") {
    die();
}

// include configurations
include_once '../../config/config.php';

// import class file
include_once '../../class/evaluation.php';

$database = new Database();
$db = $database->getConnection();

$items = new Evaluation($db);

$stmt = $items->getTeachersAndSubjects();

$itemCount = $stmt->rowCount();
if($itemCount > 0) {
    $resultArray = array();
    $resultArray["body"] = array();
    $resultArray["itemCount"] = $itemCount;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $resultData = array(
            "materia" => $materia,
            "nombre_docente" => $nombre_docente,
            "rfc_docente" => $rfc_docente,
        );
        $resultArray["body"][] = $resultData;
    }
    http_response_code(200);
    echo json_encode($resultArray["body"]);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No record found.")
    );
}
