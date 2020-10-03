<?php
  
// include database and object file
include_once '../src/config/database.php';
include_once '../src/objects/attachment_work.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$attachment_work = new Attachment_work($db);

// get id
$data = json_decode(file_get_contents("php://input"));

// set attachment_work id to be deleted
$attachment_work->id_attachment_work = $data->id_attachment_work;

// delete
if($attachment_work->forceDelete()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "attachment_work was force deleted."));
}
  
// if unable to delete
else{
  
    // set response code - 503 service unavaiattachment_workle
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to force delete attachment_work."));
}
?>