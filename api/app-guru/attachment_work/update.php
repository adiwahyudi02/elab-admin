<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/tugas.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$tugas = new tugas($db);
  
// get id to be edited
$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->id_tugas) &&
    !empty($data->title)
){
    // set ID property to be edited
    $tugas->id_tugas = $data->id_tugas;
    
    // set property values
    $tugas->id_jadwal_mapel = $data->id_jadwal_mapel;

    // set property values
    $tugas->title = $data->title;

    // set property values
    $tugas->description = $data->description;

    // set property values
    $tugas->due_date = $data->due_date;

    // set property values
    $tugas->status = $data->status;
    $tugas->updated_at = date('Y-m-d H:i:s');
    // update
    if($tugas->update()){
    
        // set response code - 200 ok
        http_response_code(200);
    
        // tell the user
        echo json_encode(array("message" => "tugas was updated."));
    }
    
    // if unable to update, tell the user
    else{
    
        // set response code - 503 service unavaitugasle
        http_response_code(503);
    
        // tell the user
        echo json_encode(array("message" => "Unable to update tugas."));
    }
}
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create tugas. Data is incomplete."));
}
?>