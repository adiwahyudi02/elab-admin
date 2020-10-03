<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../../src/config/database.php';
include_once '../../src/objects/app-guru/tugas.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare tugas object
$tugas = new Tugas($db);
  
// set ID property of record to read
$tugas->id_tugas = isset($_GET['id_tugas']) ? $_GET['id_tugas'] : die();
  
// read the details of tugas to be edited
$tugas->read();
  
if($tugas->title!=null){
    // create array
    $tugas_arr = array(
        "id_tugas" =>  $tugas->id_tugas,
        "id_jadwal_mapel" =>  $tugas->id_jadwal_mapel,
        "title" =>  $tugas->title,
        "description" =>  $tugas->description,
        "due_date" =>  $tugas->due_date,
        "status" =>  $tugas->status,
        "attachments" => $tugas->attachments,
        "created_at" => $tugas->created_at,
        "updated_at" => $tugas->updated_at
    );
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($tugas_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user tugas does not exist
    echo json_encode(array("message" => "tugas does not exist."));
}
?>