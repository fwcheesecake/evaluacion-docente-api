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
include_once '../../class/teacher.php';

$database = new Database();
$db = $database->getConnection();

$items = new Teacher($db);

$stmt = $items->getAllTeachers();

$itemCount = $stmt->rowCount();
if($itemCount > 0) {
    $studentsArray = array();
    $studentsArray["body"] = array();
    $studentsArray["itemCount"] = $itemCount;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $resultData = array(
            "rfc" => $rfc,
            "clave_area" => $clave_area,
            "curp" => $curp,
            "no_tarjeta" => $no_tarjeta,
            "nombre_completo" => $nombre_completo,
            "nombramiento" => $nombramiento,
            "tipo" => $tipo,
            "estado" => $estado
        );
        $studentsArray["body"][] = $resultData;
    }
    http_response_code(200);
    echo json_encode($studentsArray);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No record found.")
    );
}
