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

$stmt = $items -> getAllCareers();

$itemCount = $stmt -> rowCount();
if($itemCount > 0) {
    $careersArray = array();
    $careersArray["body"] = array();
    $careersArray["itemCount"] = $itemCount;

    while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $resultData = array(
            "clave_carrera" => $clave_carrera,
            "reticula" => $reticula,
            "nivel_escolar" => $nivel_escolar,
            "clave_oficial" => $clave_oficial,
            "nombre" => $nombre,
            "nombre_abreviado" => $nombre_abreviado,
            "siglas" => $siglas,
            "modalidad" => $modalidad,
            "fecha_termino" => $fecha_termino
        );
        $careersArray["body"][] = $resultData;
    }
    http_response_code(200);
    echo json_encode($careersArray);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No record found.")
    );
}
