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

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate user object
$user = new Auth($db);
 
// check email existence here
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// set product property values
$user->email = $data->email;
$email_exists = $user->emailExists();
 
// check if email exists and if password is correct
if($email_exists && password_verify($data->password, $user->password)){
 
    $token = array(
       "iss" => $iss,
       "aud" => $aud,
       "iat" => $iat,
       "nbf" => $nbf,
       "data" => array(
           "id" => $user->id,
           "nama" => $user->nama,
           "username" => $user->username,
           "email" => $user->email,
           "password" => $data->password, // password yg tidak di hash
           "role" => $user->role,
       )
    );

    // set response code
    http_response_code(200);
 
    // generate jwt
    $jwt = JWT::encode($token, $key);
    echo json_encode(
            array(
                "status" => true,
                "message" => "Successful login.",
                "jwt" => $jwt,
                "data" => array(
                    "id" => $user->id,
                    "nama" => $user->nama,
                    "username" => $user->username,
                    "email" => $user->email,
                    "password" => $data->password, // password yg tidak di hash
                    "role" => $user->role,
                )
            )
        );
}
 
// login failed
else{
 
    // set response code
    http_response_code(200);
 
    // tell the user login failed
    echo json_encode(array("status" => false,"message" => "Login failed."));
}
?>