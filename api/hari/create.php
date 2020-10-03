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
include_once '../src/objects/hari.php';
  
$database = new Database();
$db = $database->getConnection();
  
$hari = new Hari($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    !empty($data->hari)
){
  
    // set property values
    $hari->hari = $data->hari;
    $hari->id_lab = $data->id_lab;
    $hari->created_at = date('Y-m-d H:i:s');
  
    // create the hari
    if($hari->create()){

        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "Day was created."));
    }
  
    // if unable to create, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create day."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create day. Data is incomplete."));
}
?>