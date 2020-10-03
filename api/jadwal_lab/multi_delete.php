<?php
  
// include database and object file
include_once '../src/config/database.php';
include_once '../src/objects/jadwal_lab.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$jadwal_lab = new Jadwal_lab($db);

// get id
$data = json_decode(file_get_contents("php://input"));
  
// set jadwal_lab id to be deleted
$jadwal_lab->selected = $data->selected;

$jadwal_lab->deleted_at = date('Y-m-d H:i:s');

// delete
if($jadwal_lab->multiDelete()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "jadwal_lab was deleted."));
}
  
// if unable to delete
else{
  
    // set response code - 503 service unavaijadwal_lable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to delete jadwal_lab."));
}
?>