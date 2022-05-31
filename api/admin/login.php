<?php
// get request parameters
header("Access-Control-Allow-Origin: *");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: POST, PUT, DELETE, UPDATE");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// import jwt
use Firebase\JWT\JWT;
include_once '../../vendor/autoload.php';

// include configurations
include_once '../../config/config.php';

// import class file
include_once '../../class/admin.php';

$database = new Database();
$db = $database->getConnection();

$items = new Admin($db);

$stmt = $items->login();

$numOfRows = $stmt->rowCount();

if($numOfRows > 0) {
    $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

    $usuario = $dataRow['usuario'];
    $contrasena = $dataRow['contrasena'];

    if($items->contrasena == $contrasena) {
        $issuedat_claim = time(); // time issued
        $expire_claim = $issuedat_claim + (60 * 60 * 24);

        $token = array(
            "iat" => $issuedat_claim,
            "exp" => $expire_claim,

            "data" => array(
                "usuario" => $usuario,
            ));

        $jwtValue = JWT::encode($token, $database->secret_key, 'HS256');

        http_response_code(200);
        echo json_encode(
            array(
                "message" => "success",
                "idToken" => $jwtValue,
                "usuario" => $usuario,
                "expiresIn" => $expire_claim
            ));
    } else {
        http_response_code(404);
        echo json_encode(array("success" => "false"));
    }
}
?>