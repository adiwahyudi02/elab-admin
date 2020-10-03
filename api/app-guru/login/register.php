<?php

include_once '../src/config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
include_once '../libs/php-jwt-master/src/JWK.php';

use \Firebase\JWT\JWT;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, PUT, OPTIONS, DELETE');
    header('Access-Control-Allow-Headers: token, Content-Type');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    die();
}

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$data = apache_request_headers();

if (!empty($data['Authorization'])) {
    $token = $data['Authorization'];
    // get jwt
    $jwt=isset($token) ? $token : "";
    
    // if jwt is not empty
    if($jwt){
    
        // if decode succeed, show user details
        try {
            // decode jwt
            $decoded = JWT::decode($jwt, 'example_key', array('HS256'));
            http_response_code(200);
        }
    
        // if decode fails, it means jwt is invalid
        catch (Exception $e){
        
            // set response code
            http_response_code(401);
        
            // tell the user access denied  & show error message
            echo json_encode(array(
                "message" => "Access denied.",
                "error" => $e->getMessage()
            ));
            exit;
        }

    }else {
        http_response_code(401);

        // tell the user access denied  & show error message
        echo json_encode(array(
            "message" => "Access denied."
        ));
        exit;
    }
}
else {
    http_response_code(401);

    // tell the user access denied  & show error message
    echo json_encode(array(
        "message" => "Access denied."
    ));
    exit;
}
 
// files needed to connect to database
include_once '../src/config/database.php';
include_once '../src/objects/auth.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate product object
$user = new Auth($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// set product property values
$user->nama = $data->nama;
$user->username = $data->username;
$user->email = $data->email;
$user->password = $data->password;
$user->created_at = date('Y-m-d H:i:s');

// create the user
if(
    !empty($user->nama) &&
    !empty($user->username) &&
    !empty($user->email) &&
    !empty($user->password) &&
    !empty($user->created_at) &&
    $user->create()
){
 
    // set response code
    http_response_code(200);
 
    // display message: user was created
    echo json_encode(array("message" => "User was created."));
}
 
// message if unable to create user
else{
 
    // set response code
    http_response_code(400);
 
    // display message: unable to create user
    echo json_encode(array("message" => "Unable to create user."));
}
?>