<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../src/config/database.php';
  
// instantiate object
include_once '../src/objects/jadwal_mapel.php';
  
$database = new Database();
$db = $database->getConnection();
  
$jadwal_mapel = new Jadwal_mapel($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
// make sure data is not empty
if(
    !empty($data->id_jadwal_lab)
){
  
    // set property values
    $jadwal_mapel->id_kelas = $data->id_kelas;
    $jadwal_mapel->id_jadwal_lab = $data->id_jadwal_lab;
    $jadwal_mapel->id_mapel = $data->id_mapel;
    $jadwal_mapel->id_guru = $data->id_guru;
    $jadwal_mapel->jam_mulai = $data->jam_mulai;
    $jadwal_mapel->jam_selesai = $data->jam_selesai;
    $jadwal_mapel->created_at = date('Y-m-d H:i:s');
  
    // create the jadwal_mapel
    if($jadwal_mapel->create()){

        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "jadwal_mapel was created."));
    }
  
    // if unable to create, tell the user
    else{
  
        // set response code - 503 service unavaijadwal_mapelle
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create jadwal_mapel."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create jadwal_mapel. Data is incomplete."));
}
?>