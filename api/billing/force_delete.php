<?php
  
// include database and object file
include_once '../src/config/database.php';
include_once '../src/objects/billing.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$billing = new Billing($db);

// get id
$data = json_decode(file_get_contents("php://input"));

// set billing id to be deleted
$billing->id_billing = $data->id_billing;

// delete
if($billing->forceDelete()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "billing was force deleted."));
}
  
// if unable to delete
else{
  
    // set response code - 503 service unavaibillingle
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to force delete billing."));
}
?>