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
include_once '../src/objects/guru.php';
  
$database = new Database();
$db = $database->getConnection();
  
$guru = new Guru($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
// make sure data is not empty
if(
    !empty($data->nama_guru)
){
  
    // set property values
    $guru->nip = $data->nip;
    $guru->nama_guru = $data->nama_guru;
    $guru->email = $data->email;
    $guru->username = $data->username;
    $guru->password = $data->password;
    $guru->created_at = date('Y-m-d H:i:s');
  
    // create the guru
    if($guru->create()){

        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "guru was created."));
    }
  
    // if unable to create, tell the user
    else{
  
        // set response code - 503 service unavaigurule
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create guru."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create guru. Data is incomplete."));
}
?>