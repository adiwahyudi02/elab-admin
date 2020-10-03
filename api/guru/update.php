<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/guru.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$guru = new Guru($db);
  
// get id to be edited
$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->id_guru) &&
    !empty($data->nama_guru)
){
    // set ID property to be edited
    $guru->id_guru = $data->id_guru;

    // set property values
    $guru->nip = $data->nip;

    // set property values
    $guru->nama_guru = $data->nama_guru;

    // set property values
    $guru->email = $data->email;

    // set property values
    $guru->username = $data->username;

    // set property values
    $guru->password = $data->password;
    $guru->updated_at = date('Y-m-d H:i:s');
    // update
    if($guru->update()){
    
        // set response code - 200 ok
        http_response_code(200);
    
        // tell the user
        echo json_encode(array("message" => "guru was updated."));
    }
    
    // if unable to update, tell the user
    else{
    
        // set response code - 503 service unavaigurule
        http_response_code(503);
    
        // tell the user
        echo json_encode(array("message" => "Unable to update guru."));
    }
}
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create guru. Data is incomplete."));
}
?>