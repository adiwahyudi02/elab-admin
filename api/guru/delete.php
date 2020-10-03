<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object file
include_once '../src/config/database.php';
include_once '../src/objects/guru.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$guru = new Guru($db);

// get id
$data = json_decode(file_get_contents("php://input"));
  
// set guru id to be deleted
$guru->id_guru = $data->id_guru;
$guru->deleted_at = date('Y-m-d H:i:s');

// delete
if($guru->delete()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "guru was deleted."));
}
  
// if unable to delete
else{
  
    // set response code - 503 service unavaigurule
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to delete guru."));
}
?>