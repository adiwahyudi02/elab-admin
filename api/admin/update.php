<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/admin.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$admin = new Admin($db);
  
// get id to be edited
$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->id) &&
    !empty($data->nama)
){
    // set ID property to be edited
    $admin->id = $data->id;
    
    // set property values
    $admin->nama = $data->nama;
    
    // set property values
    $admin->username = $data->username;

    // set property values
    $admin->email = $data->email;

    // set property values
    $admin->password = $data->password;
    $admin->updated_at = date('Y-m-d H:i:s');
    
    // update
    if($admin->update()){
    
        // set response code - 200 ok
        http_response_code(200);
    
        // tell the user
        echo json_encode(array("message" => "admin was updated."));
    }
    
    // if unable to update, tell the user
    else{
    
        // set response code - 503 service unavaiadminle
        http_response_code(503);
    
        // tell the user
        echo json_encode(array("message" => "Unable to update admin."));
    }
}
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create admin. Data is incomplete."));
}
?>