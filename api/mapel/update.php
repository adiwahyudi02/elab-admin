<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/mapel.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$mapel = new Mapel($db);
  
// get id to be edited
$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->id_mapel) &&
    !empty($data->nama_mapel)
){
    // set ID property to be edited
    $mapel->id_mapel = $data->id_mapel;
    
    // set property values
    $mapel->nama_mapel = $data->nama_mapel;
    $mapel->updated_at = date('Y-m-d H:i:s');
    
    // update
    if($mapel->update()){
    
        // set response code - 200 ok
        http_response_code(200);
    
        // tell the user
        echo json_encode(array("message" => "mapel was updated."));
    }
    
    // if unable to update, tell the user
    else{
    
        // set response code - 503 service unavaimapelle
        http_response_code(503);
    
        // tell the user
        echo json_encode(array("message" => "Unable to update mapel."));
    }
}
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create mapel. Data is incomplete."));
}
?>