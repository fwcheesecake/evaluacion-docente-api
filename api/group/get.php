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
include_once '../../class/group.php';

$database = new Database();
$db = $database->getConnection();

$items = new Group($db);

$stmt = $items->getGroup();

$itemCount = $stmt->rowCount();
if($itemCount > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($row);
    $resultData = array(
        "periodo" => $periodo,
        "materia" => $materia,
        "grupo" => $grupo,
        "capacidad" => $capacidad,
        "alumnos_inscritos" => $alumnos_inscritos,
        "rfc_docente" => $rfc_docente
    );
    http_response_code(200);
    echo json_encode($resultData);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No record found.")
    );
}
