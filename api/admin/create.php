<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../src/config/database.php';
  
// instantiate object
include_once '../src/objects/admin.php';
  
$database = new Database();
$db = $database->getConnection();
  
$admin = new Admin($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    !empty($data->nama)
){
  
    // set property values
    $admin->nama = $data->nama;
    $admin->username = $data->username;
    $admin->email = $data->email;
    $admin->password = $data->password;
    $admin->created_at = date('Y-m-d H:i:s');
  
    // create the admin
    if($admin->create()){

        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "admin was created."));
    }
  
    // if unable to create, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create admin."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create admin. Data is incomplete."));
}
?>