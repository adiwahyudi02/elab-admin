<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/jadwal_lab.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare jadwal_lab object
$jadwal_lab = new Jadwal_lab($db);
  
// set ID property of record to read
$jadwal_lab->id_jadwal_lab = isset($_GET['id_jadwal_lab']) ? $_GET['id_jadwal_lab'] : die();
  
// read the details of jadwal_lab to be edited
$jadwal_lab->read();
  
if($jadwal_lab->jam_mulai!=null){
    // create array
    $jadwal_lab_arr = array(
        "id_jadwal_lab" =>  $jadwal_lab->id_jadwal_lab,
        "id_kelas" =>  $jadwal_lab->id_kelas,
        "nama_kelas" =>  $jadwal_lab->nama_kelas,
        "id_hari" =>  $jadwal_lab->id_hari,
        "hari" =>  $jadwal_lab->hari,
        "jam_mulai" =>  $jadwal_lab->jam_mulai,
        "jam_selesai" => $jadwal_lab->jam_selesai,
        "created_at" => $jadwal_lab->created_at,
        "updated_at" => $jadwal_lab->updated_at
    );
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($jadwal_lab_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user jadwal_lab does not exist
    echo json_encode(array("message" => "jadwal_lab does not exist."));
}
?>