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
include_once '../class/students.php';

$database = new Database();
$db = $database -> getConnection();

$items = new Users($db);

$stmt = $items -> logIn();

$numOfRows = $stmt->rowCount();

if($numOfRows > 0) {
    $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

    $user = $dataRow['usuario'];
    $pass = $dataRow['contrasena'];


    if($items->contrasena == $pass) {
        $secret_key = "23uidfjiq83fevaldocente2022ddafio38c";
        $issuedat_claim = time(); // time issued
        $expire_claim = $issuedat_claim + (60*60*24);

        $token = array(
            "iat" => $issuedat_claim,
            "exp" => $expire_claim,

            "data" => array(
                "usuario" => $user,
            ));

        $jwtValue = JWT::encode($token, $secret_key, 'HS256');

        echo json_encode(
            array(
                "message" => "success",
                "token" => $jwtValue,
                "usuario" => $user,
                "expiry" => $expire_claim
            ));
    } else {
        echo json_encode(array("success" => "false"));
    }
}
?>