<?php
  
// include database and object file
include_once '../src/config/database.php';
include_once '../src/objects/tugas.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$tugas = new Tugas($db);

// get id
$data = json_decode(file_get_contents("php://input"));
  
// set tugas id to be restored
$tugas->selected = $data->selected;

// restore
if($tugas->multiRestore()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "tugas was restored."));
}
  
// if unable to restore
else{
  
    // set response code - 503 service unavaitugasle
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to restore tugas."));
}
?>