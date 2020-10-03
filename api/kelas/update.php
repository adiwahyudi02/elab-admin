<?php
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/kelas.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$kelas = new Kelas($db);
  
// get id to be edited
$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->id_kelas) &&
    !empty($data->nama_kelas)
){
    // set ID property to be edited
    $kelas->id_kelas = $data->id_kelas;
    
    // set property values
    $kelas->nama_kelas = $data->nama_kelas;
    $kelas->updated_at = date('Y-m-d H:i:s');
    
    // update
    if($kelas->update()){
    
        // set response code - 200 ok
        http_response_code(200);
    
        // tell the user
        echo json_encode(array("message" => "kelas was updated."));
    }
    
    // if unable to update, tell the user
    else{
    
        // set response code - 503 service unavaikelasle
        http_response_code(503);
    
        // tell the user
        echo json_encode(array("message" => "Unable to update kelas."));
    }
}
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create kelas. Data is incomplete."));
}
?>