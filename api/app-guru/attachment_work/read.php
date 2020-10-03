<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/attachment_work.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare attachment_work object
$attachment_work = new Attachment_work($db);
  
// set ID property of record to read
$attachment_work->id_attachment_work = isset($_GET['id_attachment_work']) ? $_GET['id_attachment_work'] : die();
  
// read the details of attachment_work to be edited
$attachment_work->read();
  
if($attachment_work->nama_file!=null){
    // create array
    $attachment_work_arr = array(
        "id_attachment_work" =>  $attachment_work->id_attachment_work,
        "id_work" =>  $attachment_work->id_work,
        "nama_file" => $attachment_work->nama_file,
        "attachment" =>  $attachment_work->attachment,
        "created_at" => $attachment_work->created_at,
        "updated_at" => $attachment_work->updated_at
    );
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($attachment_work_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user attachment_work does not exist
    echo json_encode(array("message" => "attachment_work does not exist."));
}
?>