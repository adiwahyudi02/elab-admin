<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/komputer.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$komputer = new Komputer($db);
  
// get id to be edited
$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->id_komputer) &&
    !empty($data->nama_komputer)
){
    // set ID property to be edited
    $komputer->id_komputer = $data->id_komputer;
    
    // set property values
    $komputer->nama_komputer = $data->nama_komputer;

    // set property values
    $komputer->spesifikasi = $data->spesifikasi;

    // set property values
    $komputer->id_lab = $data->id_lab;

    // set property values
    $komputer->ip_address = $data->ip_address;
    $komputer->updated_at = date('Y-m-d H:i:s');
    // update
    if($komputer->update()){
    
        // set response code - 200 ok
        http_response_code(200);
    
        // tell the user
        echo json_encode(array("message" => "komputer was updated."));
    }
    
    // if unable to update, tell the user
    else{
    
        // set response code - 503 service unavaikomputerle
        http_response_code(503);
    
        // tell the user
        echo json_encode(array("message" => "Unable to update komputer."));
    }
}
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create komputer. Data is incomplete."));
}
?>