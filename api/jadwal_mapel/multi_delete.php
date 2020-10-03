<?php
  
// include database and object file
include_once '../src/config/database.php';
include_once '../src/objects/jadwal_mapel.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$jadwal_mapel = new Jadwal_mapel($db);

// get id
$data = json_decode(file_get_contents("php://input"));
  
// set jadwal_mapel id to be deleted
$jadwal_mapel->selected = $data->selected;

$jadwal_mapel->deleted_at = date('Y-m-d H:i:s');

// delete
if($jadwal_mapel->multiDelete()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "jadwal_mapel was deleted."));
}
  
// if unable to delete
else{
  
    // set response code - 503 service unavaijadwal_mapelle
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to delete jadwal_mapel."));
}
?>