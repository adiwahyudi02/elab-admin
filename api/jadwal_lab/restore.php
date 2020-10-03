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
  
// set jadwal_lab id to be restored
$jadwal_lab->id_jadwal_lab = $data->id_jadwal_lab;

// restore
if($jadwal_lab->restore()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "jadwal_lab was restored."));
}
  
// if unable to restore
else{
  
    // set response code - 503 service unavaijadwal_lable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to restore jadwal_lab."));
}
?>