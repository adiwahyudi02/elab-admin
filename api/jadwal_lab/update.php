<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/jadwal_lab.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$jadwal_lab = new Jadwal_lab($db);
  
// get id to be edited
$data = json_decode(file_get_contents("php://input"));


if(
    !empty($data->id_jadwal_lab) &&
    !empty($data->id_kelas) &&
    !empty($data->id_hari) &&
    !empty($data->jam_mulai) &&
    !empty($data->jam_selesai)
){
    // set ID property to be edited
    $jadwal_lab->id_jadwal_lab = $data->id_jadwal_lab;
    
    // set property values
    $jadwal_lab->id_kelas = $data->id_kelas;

    // set property values
    $jadwal_lab->id_hari = $data->id_hari;

    // set property values
    $jadwal_lab->jam_mulai = $data->jam_mulai;

    // set property values
    $jadwal_lab->jam_selesai = $data->jam_selesai;
    $jadwal_lab->updated_at = date('Y-m-d H:i:s');
    // update
    if($jadwal_lab->update()){
    
        // set response code - 200 ok
        http_response_code(200);
    
        // tell the user
        echo json_encode(array("message" => "jadwal_lab was updated."));
    }
    
    // if unable to update, tell the user
    else{
    
        // set response code - 503 service unavaijadwal_lable
        http_response_code(503);
    
        // tell the user
        echo json_encode(array("message" => "Unable to update jadwal_lab."));
    }
}
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create jadwal_lab. Data is incomplete."));
}
?>