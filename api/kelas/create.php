<?php
  
// get database connection
include_once '../src/config/database.php';
  
// instantiate object
include_once '../src/objects/kelas.php';
  
$database = new Database();
$db = $database->getConnection();
  
$kelas = new Kelas($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    !empty($data->nama_kelas)
){
  
    // set property values
    $kelas->nama_kelas = $data->nama_kelas;
    $kelas->created_at = date('Y-m-d H:i:s');
  
    // create the kelas
    if($kelas->create()){

        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "kelas was created."));
    }
  
    // if unable to create, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create kelas."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create kelas. Data is incomplete."));
}
?>