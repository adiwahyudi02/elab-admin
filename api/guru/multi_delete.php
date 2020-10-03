<?php
  
// include database and object file
include_once '../src/config/database.php';
include_once '../src/objects/guru.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$guru = new Guru($db);

// get id
$data = json_decode(file_get_contents("php://input"));
  
// set guru id to be deleted
$guru->selected = $data->selected;

$guru->deleted_at = date('Y-m-d H:i:s');

// delete
if($guru->multiDelete()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "guru was deleted."));
}
  
// if unable to delete
else{
  
    // set response code - 503 service unavaigurule
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to delete guru."));
}
?>