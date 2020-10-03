<?php
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/lab.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$lab = new Lab($db);
  
// get id to be edited
$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->id_lab) &&
    !empty($data->nama_lab)
){
    // set ID property to be edited
    $lab->id_lab = $data->id_lab;
    
    // set property values
    $lab->nama_lab = $data->nama_lab;
    // $lab->created_at = $data->created_at;
    
    // update
    if($lab->update()){
    
        // set response code - 200 ok
        http_response_code(200);
    
        // tell the user
        echo json_encode(array("message" => "lab was updated."));
    }
    
    // if unable to update, tell the user
    else{
    
        // set response code - 503 service unavailable
        http_response_code(503);
    
        // tell the user
        echo json_encode(array("message" => "Unable to update lab."));
    }
}
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create lab. Data is incomplete."));
}
?>