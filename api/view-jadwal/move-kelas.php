<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/view-jadwal.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$move = new Jadwal($db);
  
// get id to be edited
$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->columnId)
){
    // set ID property to be edited
    $move->columnId = $data->columnId;
    $move->result = $data->result;
    $move->data = $data->data;
    $move->updated_at = date('Y-m-d H:i:s');
    
    // update
    if($move->moveKelas()){
    
        // set response code - 200 ok
        http_response_code(200);
    
        // tell the user
        echo json_encode(array("message" => "move was updated."));
    }
}
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create move. Data is incomplete."));
}
?>
