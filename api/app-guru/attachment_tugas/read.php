<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/attachment_tugas.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare attachment_tugas object
$attachment_tugas = new Attachment_tugas($db);
  
// set ID property of record to read
$attachment_tugas->id_attachment_tugas = isset($_GET['id_attachment_tugas']) ? $_GET['id_attachment_tugas'] : die();
  
// read the details of attachment_tugas to be edited
$attachment_tugas->read();
  
if($attachment_tugas->nama_file!=null){
    // create array
    $attachment_tugas_arr = array(
        "id_attachment_tugas" =>  $attachment_tugas->id_attachment_tugas,
        "id_tugas" => $attachment_tugas->id_tugas,
        "nama_file" =>  $attachment_tugas->nama_file,
        "attachment" => $attachment_tugas->attachment,
        "created_at" => $attachment_tugas->created_at,
        "updated_at" => $attachment_tugas->updated_at
    );
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($attachment_tugas_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user attachment_tugas does not exist
    echo json_encode(array("message" => "attachment_tugas does not exist."));
}
?>