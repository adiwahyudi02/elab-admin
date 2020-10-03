<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/jadwal_mapel.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare jadwal_mapel object
$jadwal_mapel = new Jadwal_mapel($db);
  
// set ID property of record to read
$jadwal_mapel->id_jadwal_mapel = isset($_GET['id_jadwal_mapel']) ? $_GET['id_jadwal_mapel'] : die();
  
// read the details of jadwal_mapel to be edited
$jadwal_mapel->read();
  
if($jadwal_mapel->jam_mulai!=null){
    // create array
    $jadwal_mapel_arr = array(
        "id_jadwal_mapel" =>  $jadwal_mapel->id_jadwal_mapel,
        "id_kelas" =>  $jadwal_mapel->id_kelas,
        "nama_kelas" =>  $jadwal_mapel->nama_kelas,
        "id_jadwal_lab" =>  $jadwal_mapel->id_jadwal_lab,
        "id_mapel" =>  $jadwal_mapel->id_mapel,
        "nama_mapel" =>  $jadwal_mapel->nama_mapel,
        "id_guru" =>  $jadwal_mapel->id_guru,
        "nama_guru" =>  $jadwal_mapel->nama_guru,
        "jam_mulai" =>  $jadwal_mapel->jam_mulai,
        "jam_selesai" => $jadwal_mapel->jam_selesai,
        "created_at" => $jadwal_mapel->created_at,
        "updated_at" => $jadwal_mapel->updated_at
    );
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($jadwal_mapel_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user jadwal_mapel does not exist
    echo json_encode(array("message" => "jadwal_mapel does not exist."));
}
?>