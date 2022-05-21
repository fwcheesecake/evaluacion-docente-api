<?php
// get request parameters
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: POST, PUT, DELETE, UPDATE");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// import jwt
use Firebase\JWT\JWT;

// include configurations
include_once '../config/config.php';

// import class file
include_once '../class/student.php';

$database = new Database();
$db = $database -> getConnection();

$items = new Student($db);

$stmt = $items -> logIn();

$numOfRows = $stmt->rowCount();

if($numOfRows > 0) {
    $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

    $control = $dataRow['control'];
    $nip = $dataRow['nip'];

    if($items->nip == $nip) {
        $issuedat_claim = time(); // time issued
        $expire_claim = $issuedat_claim + (60*60*24);

        $token = array(
            "iat" => $issuedat_claim,
            "exp" => $expire_claim,

            "data" => array(
                "control" => $control,
            ));

        $jwtValue = JWT::encode($token, $dataRow->secret_key, 'HS256');

        echo json_encode(
            array(
                "message" => "success",
                "token" => $jwtValue,
                "usuario" => $control,
                "expiry" => $expire_claim
            ));
    } else {
        echo json_encode(array("success" => "false"));
    }
}
?>