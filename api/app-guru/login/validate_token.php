<?php
// required headers
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: token, Content-Type');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    die();
}

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../src/config/database.php';
include_once '../src/objects/auth.php';
include_once '../src/config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
include_once '../libs/php-jwt-master/src/JWK.php';

use \Firebase\JWT\JWT;


// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// get jwt
$jwt=isset($data->jwt) ? $data->jwt : "";
 
// if jwt is not empty
if($jwt){
 
    // if decode succeed, show user details
    try {
        // decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));
 
        // set response code
        http_response_code(200);
 
        // show user details
        if ($decoded->data->role == 'admin') {
            echo json_encode(array(
                "status" => true,
                "message" => "Access granted.",
                "data" => $decoded->data
            ));    
        }else{
            http_response_code(200);
    
            // tell the user access denied  & show error message
            echo json_encode(array(
                "status" => false,
                "message" => "Access denied."
            ));
        }
        
 
    }
 
    // if decode fails, it means jwt is invalid
    catch (Exception $e){
    
        // set response code
        http_response_code(200);
    
        // tell the user access denied  & show error message
        echo json_encode(array(
            "status" => false,
            "message" => "Access denied."
        ));
    }
}
 // show error message if jwt is empty
else{
 
    // set response code
    http_response_code(200);
 
    // tell the user access denied
    echo json_encode(array(
        "status" => false,
        "message" => "Access denied."
    ));
}
?>
