<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/jadwal_mapel.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$jadwal_mapel = new Jadwal_mapel($db);
  
// get id to be edited
$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->id_jadwal_mapel) &&
    !empty($data->jam_mulai)
){
    // set ID property to be edited
    $jadwal_mapel->id_jadwal_mapel = $data->id_jadwal_mapel;
    
    // set property values
    $jadwal_mapel->id_kelas = $data->id_kelas;

    // set property values
    $jadwal_mapel->id_jadwal_lab = $data->id_jadwal_lab;

    // set property values
    $jadwal_mapel->id_mapel = $data->id_mapel;

    // set property values
    $jadwal_mapel->id_guru = $data->id_guru;

    // set property values
    $jadwal_mapel->jam_mulai = $data->jam_mulai;

    // set property values
    $jadwal_mapel->jam_selesai = $data->jam_selesai;
    $jadwal_mapel->updated_at = date('Y-m-d H:i:s');
    // update
    if($jadwal_mapel->update()){
    
        // set response code - 200 ok
        http_response_code(200);
    
        // tell the user
        echo json_encode(array("message" => "jadwal_mapel was updated."));
    }
    
    // if unable to update, tell the user
    else{
    
        // set response code - 503 service unavaijadwal_mapelle
        http_response_code(503);
    
        // tell the user
        echo json_encode(array("message" => "Unable to update jadwal_mapel."));
    }
}
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create jadwal_mapel. Data is incomplete."));
}
?>