<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../src/config/database.php';
  
// instantiate object
include_once '../src/objects/mapel.php';
  
$database = new Database();
$db = $database->getConnection();
  
$mapel = new Mapel($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    !empty($data->nama_mapel)
){
  
    // set property values
    $mapel->nama_mapel = $data->nama_mapel;
    $mapel->created_at = date('Y-m-d H:i:s');
  
    // create the mapel
    if($mapel->create()){

        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "mapel was created."));
    }
  
    // if unable to create, tell the user
    else{
  
        // set response code - 503 service unavaimapelle
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create mapel."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create mapel. Data is incomplete."));
}
?>