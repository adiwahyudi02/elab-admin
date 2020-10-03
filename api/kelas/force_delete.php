<?php
  
// include database and object file
include_once '../src/config/database.php';
include_once '../src/objects/kelas.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$kelas = new Kelas($db);

// get id
$data = json_decode(file_get_contents("php://input"));

// set kelas id to be deleted
$kelas->id_kelas = $data->id_kelas;

// delete
if($kelas->forceDelete()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "kelas was force deleted."));
}
  
// if unable to delete
else{
  
    // set response code - 503 service unavaikelasle
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to force delete kelas."));
}
?>