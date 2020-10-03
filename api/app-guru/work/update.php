<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../../src/config/database.php';
include_once '../../src/objects/app-guru/work.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$work = new Work($db);
  
// get id to be edited
$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->id_work) &&
    !empty($data->status)
){
    // set ID property to be edited
    $work->id_work = $data->id_work;
    
    // set property values
    $work->nis = $data->nis;

    // set property values
    $work->id_tugas = $data->id_tugas;

    // set property values
    $work->status = $data->status;
    $work->updated_at = date('Y-m-d H:i:s');
    // update
    if($work->update()){
    
        // set response code - 200 ok
        http_response_code(200);
    
        // tell the user
        echo json_encode(array("message" => "work was updated."));
    }
    
    // if unable to update, tell the user
    else{
    
        // set response code - 503 service unavaiworkle
        http_response_code(503);
    
        // tell the user
        echo json_encode(array("message" => "Unable to update work."));
    }
}
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create work. Data is incomplete."));
}
?>