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
  
// set guru id to be force deleted
$guru->selected = $data->selected;

// force delete
if($guru->multiForceDelete()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "guru was force deleted."));
}
  
// if unable to force delete
else{
  
    // set response code - 503 service unavaigurule
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to force delete guru."));
}
?>