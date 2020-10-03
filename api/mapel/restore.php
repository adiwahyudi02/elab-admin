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
  
// set mapel id to be restored
$mapel->id_mapel = $data->id_mapel;

// restore
if($mapel->restore()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "mapel was restored."));
}
  
// if unable to restore
else{
  
    // set response code - 503 service unavaimapelle
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to restore mapel."));
}
?>