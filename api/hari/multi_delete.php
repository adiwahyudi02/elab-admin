<?php
  
// include database and object file
include_once '../src/config/database.php';
include_once '../src/objects/hari.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$hari = new Hari($db);

// get id
$data = json_decode(file_get_contents("php://input"));
  
// set hari id to be deleted
$hari->selected = $data->selected;

$hari->deleted_at = date('Y-m-d H:i:s');

// delete
if($hari->multiDelete()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "hari was deleted."));
}
  
// if unable to delete
else{
  
    // set response code - 503 service unavaiharile
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to delete hari."));
}
?>