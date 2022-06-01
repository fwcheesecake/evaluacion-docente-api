<?php
// get request parameters
header("Access-Control-Allow-Origin: *");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: POST, PUT, DELETE, UPDATE");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$method = $_SERVER['REQUEST_METHOD'];
if($method == "OPTIONS") {
    die();
}

// import jwt
use Firebase\JWT\JWT;
include_once '../../vendor/autoload.php';

// include configurations
include_once '../../config/config.php';

// import class file
include_once '../../class/student.php';

$database = new Database();
$db = $database->getConnection();

$items = new Student($db);

$stmt = $items->login();

$numOfRows = $stmt->rowCount();

if($numOfRows > 0) {
    $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

    $control = $dataRow['control'];
    $nip = $dataRow['nip'];
    $nombre_completo = $dataRow['nombre_completo'];

    if($items->nip == $nip) {
        $issuedat_claim = time(); // time issued
        $expire_claim = $issuedat_claim + (60 * 60 * 24);

        $token = array(
            "iat" => $issuedat_claim,
            "exp" => $expire_claim,

            "data" => array(
                "usuario" => $control,
            ));

        $jwtValue = JWT::encode($token, $database->secret_key, 'HS256');

        http_response_code(200);
        echo json_encode(
            array(
                "message" => "success",
                "idToken" => $jwtValue,
                "expiresIn" => $expire_claim,

                "control" => $control,
                "nombre_completo" => $nombre_completo
            ));
    } else {
        http_response_code(404);
        echo json_encode(array("success" => "false"));
    }
}
?>