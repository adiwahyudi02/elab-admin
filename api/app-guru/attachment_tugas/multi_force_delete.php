<?php
  
// include database and object file
include_once '../src/config/database.php';
include_once '../src/objects/attachment_tugas.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$attachment_tugas = new Attachment_tugas($db);

// get id
$data = json_decode(file_get_contents("php://input"));
  
// set attachment_tugas id to be force deleted
$attachment_tugas->selected = $data->selected;

// force delete
if($attachment_tugas->multiForceDelete()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "attachment_tugas was force deleted."));
}
  
// if unable to force delete
else{
  
    // set response code - 503 service unavaiattachment_tugasle
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to force delete attachment_tugas."));
}
?>