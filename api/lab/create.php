<?php
// get database connection
include_once '../src/config/database.php';
  
// instantiate object
include_once '../src/objects/lab.php';
  
$database = new Database();
$db = $database->getConnection();
  
$lab = new Lab($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    !empty($data->nama_lab)
){
  
    // set property values
    $lab->nama_lab = $data->nama_lab;
    $lab->created_at = date('Y-m-d H:i:s');
  
    // create the lab
    if($lab->create()){

        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "lab was created."));
    }
  
    // if unable to create, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create lab."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create lab. Data is incomplete."));
}
?>