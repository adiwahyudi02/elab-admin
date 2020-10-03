<?php
  
// include database and object file
include_once '../src/config/database.php';
include_once '../src/objects/komputer.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$komputer = new Komputer($db);

// get id
$data = json_decode(file_get_contents("php://input"));
  
// set komputer id to be force deleted
$komputer->selected = $data->selected;

// force delete
if($komputer->multiForceDelete()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "komputer was force deleted."));
}
  
// if unable to force delete
else{
  
    // set response code - 503 service unavaikomputerle
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to force delete komputer."));
}
?>