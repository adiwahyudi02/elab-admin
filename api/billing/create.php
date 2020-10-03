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
include_once '../src/objects/billing.php';
  
$database = new Database();
$db = $database->getConnection();
  
$billing = new Billing($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    !empty($data->date_time)
){
  
    // set property values
    $billing->id_komputer = $data->id_komputer;
    $billing->nis = $data->nis;
    $billing->date_time = $data->date_time;
    $billing->id_mapel = $data->id_mapel;
    $billing->id_guru = $data->id_guru;
    $billing->created_at = date('Y-m-d H:i:s');
  
    // create the billing
    if($billing->create()){

        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "billing was created."));
    }
  
    // if unable to create, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create billing."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create billing. Data is incomplete."));
}
?>