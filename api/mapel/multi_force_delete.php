<?php
  
// include database and object file
include_once '../src/config/database.php';
include_once '../src/objects/mapel.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$mapel = new Mapel($db);

// get id
$data = json_decode(file_get_contents("php://input"));
  
// set mapel id to be force deleted
$mapel->selected = $data->selected;

// force delete
if($mapel->multiForceDelete()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "mapel was force deleted."));
}
  
// if unable to force delete
else{
  
    // set response code - 503 service unavaimapelle
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to force delete mapel."));
}
?>