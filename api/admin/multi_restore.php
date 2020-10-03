<?php
  
// include database and object file
include_once '../src/config/database.php';
include_once '../src/objects/admin.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$admin = new Admin($db);

// get id
$data = json_decode(file_get_contents("php://input"));
  
// set admin id to be restored
$admin->selected = $data->selected;

// restore
if($admin->multiRestore()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "admin was restored."));
}
  
// if unable to restore
else{
  
    // set response code - 503 service unavaiadminle
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to restore admin."));
}
?>