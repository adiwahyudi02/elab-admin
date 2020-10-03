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
include_once '../src/objects/jadwal_lab.php';
  
$database = new Database();
$db = $database->getConnection();
  
$jadwal_lab = new Jadwal_lab($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
// make sure data is not empty
if(
    !empty($data->jam_mulai)
){
  
    // set property values
    $jadwal_lab->id_kelas = $data->id_kelas;
    $jadwal_lab->id_hari = $data->id_hari;
    $jadwal_lab->jam_mulai = $data->jam_mulai;
    $jadwal_lab->jam_selesai = $data->jam_selesai;
    $jadwal_lab->created_at = date('Y-m-d H:i:s');
  
    // create the jadwal_lab
    if($jadwal_lab->create()){

        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "jadwal_lab was created."));
    }
  
    // if unable to create, tell the user
    else{
  
        // set response code - 503 service unavaijadwal_lable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create jadwal_lab."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create jadwal_lab. Data is incomplete."));
}
?>