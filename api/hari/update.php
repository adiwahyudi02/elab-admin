<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/hari.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$hari = new Hari($db);
  
// get id to be edited
$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->id_hari) &&
    !empty($data->hari)
){
    // set ID property to be edited
    $hari->id_hari = $data->id_hari;
    
    // set property values
    $hari->hari = $data->hari;
    $hari->id_lab = $data->id_lab;
    $hari->updated_at = date('Y-m-d H:i:s');
    
    // update
    if($hari->update()){
    
        // set response code - 200 ok
        http_response_code(200);
    
        // tell the user
        echo json_encode(array("message" => "hari was updated."));
    }
    
    // if unable to update, tell the user
    else{
    
        // set response code - 503 service unavaiharile
        http_response_code(503);
    
        // tell the user
        echo json_encode(array("message" => "Unable to update hari."));
    }
}
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create hari. Data is incomplete."));
}
?>