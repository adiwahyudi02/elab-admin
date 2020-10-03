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
  
// set komputer id to be deleted
$komputer->selected = $data->selected;

$komputer->deleted_at = date('Y-m-d H:i:s');

// delete
if($komputer->multiDelete()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "komputer was deleted."));
}
  
// if unable to delete
else{
  
    // set response code - 503 service unavaikomputerle
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to delete komputer."));
}
?>