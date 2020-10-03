<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../../src/config/database.php';
include_once '../../src/objects/app-guru/work.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare work object
$work = new Work($db);
  
// set ID property of record to read
$work->id_work = isset($_GET['id_work']) ? $_GET['id_work'] : die();

// read the details of work to be edited
$work->read();
  
if($work->status!=null){
    // create array
    $work_arr = array(
        "id_work" =>  $work->id_work,
        "nis" => $work->nis,
        "nama" => $work->nama,
        "id_tugas" => $work->id_tugas,
        "status" => $work->status,
        "created_at" => $work->created_at,
        "updated_at" => $work->updated_at
    );
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($work_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user work does not exist
    echo json_encode(array("message" => "work does not exist."));
}
?>