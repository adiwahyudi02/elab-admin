<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
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
  
// set mapel id to be deleted
$mapel->id_mapel = $data->id_mapel;
$mapel->deleted_at = date('Y-m-d H:i:s');

// delete
if($mapel->delete()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "mapel was deleted."));
}
  
// if unable to delete
else{
  
    // set response code - 503 service unavaimapelle
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to delete mapel."));
}
?>