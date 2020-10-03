<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/billing.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$billing = new Billing($db);
  
// get id to be edited
$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->id_billing) &&
    !empty($data->date_time)
){
    // set ID property to be edited
    $billing->id_billing = $data->id_billing;
    
    // set property values
    $billing->id_komputer = $data->id_komputer;

    // set property values
    $billing->nis = $data->nis;

    // set property values
    $billing->date_time = $data->date_time;

    // set property values
    $billing->id_mapel = $data->id_mapel;

    // set property values
    $billing->id_guru = $data->id_guru;
    $billing->updated_at = date('Y-m-d H:i:s');
    // update
    if($billing->update()){
    
        // set response code - 200 ok
        http_response_code(200);
    
        // tell the user
        echo json_encode(array("message" => "billing was updated."));
    }
    
    // if unable to update, tell the user
    else{
    
        // set response code - 503 service unavaibillingle
        http_response_code(503);
    
        // tell the user
        echo json_encode(array("message" => "Unable to update billing."));
    }
}
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create billing. Data is incomplete."));
}
?>