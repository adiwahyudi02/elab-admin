<?php
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/kelas.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare kelas object
$kelas = new Kelas($db);
  
// set ID property of record to read
$kelas->id_kelas = isset($_GET['id_kelas']) ? $_GET['id_kelas'] : die();
  
// read the details of kelas to be edited
$kelas->read();
  
if($kelas->nama_kelas!=null){
    // create array
    $kelas_arr = array(
        "id_kelas" =>  $kelas->id_kelas,
        "nama_kelas" => $kelas->nama_kelas,
        "created_at" => $kelas->created_at,
        "updated_at" => $kelas->updated_at
    );
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($kelas_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user kelas does not exist
    echo json_encode(array("message" => "kelas does not exist."));
}
?>