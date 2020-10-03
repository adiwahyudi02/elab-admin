<?php
  
// include database and object file
include_once '../src/config/database.php';
include_once '../src/objects/lab.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$lab = new Lab($db);

// get id
$data = json_decode(file_get_contents("php://input"));

// set lab id to be deleted
$lab->id_lab = $data->id_lab;

// delete
if($lab->forceDelete()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "lab was force deleted."));
}
  
// if unable to delete
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to force delete lab."));
}
?>