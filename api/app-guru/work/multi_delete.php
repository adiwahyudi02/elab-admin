<?php
  
// include database and object file
include_once '../../src/config/database.php';
include_once '../../src/objects/app-guru/work.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$work = new Work($db);

// get id
$data = json_decode(file_get_contents("php://input"));
  
// set work id to be deleted
$work->selected = $data->selected;

$work->deleted_at = date('Y-m-d H:i:s');

// delete
if($work->multiDelete()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "work was deleted."));
}
  
// if unable to delete
else{
  
    // set response code - 503 service unavaiworkle
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to delete work."));
}
?>