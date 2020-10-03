<?php
  
// include database and object file
include_once '../../src/config/database.php';
include_once '../../src/objects/app-guru/tugas.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$tugas = new Tugas($db);

// get id
$data = json_decode(file_get_contents("php://input"));
  
// set tugas id to be deleted
$tugas->selected = $data->selected;

$tugas->deleted_at = date('Y-m-d H:i:s');

// delete
if($tugas->multiDelete()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "tugas was deleted."));
}
  
// if unable to delete
else{
  
    // set response code - 503 service unavaitugasle
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to delete tugas."));
}
?>