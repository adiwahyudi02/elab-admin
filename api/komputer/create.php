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
include_once '../src/objects/komputer.php';
  
$database = new Database();
$db = $database->getConnection();
  
$komputer = new Komputer($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    !empty($data->nama_komputer)
){
  
    // set property values
    $komputer->nama_komputer = $data->nama_komputer;
    $komputer->spesifikasi = $data->spesifikasi;
    $komputer->id_lab = $data->id_lab;
    $komputer->ip_address = $data->ip_address;
    $komputer->created_at = date('Y-m-d H:i:s');
  
    // create the komputer
    if($komputer->create()){

        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "komputer was created."));
    }
  
    // if unable to create, tell the user
    else{
  
        // set response code - 503 service unavaikomputerle
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create komputer."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create komputer. Data is incomplete."));
}
?>