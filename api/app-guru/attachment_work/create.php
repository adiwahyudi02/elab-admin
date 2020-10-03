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
include_once '../src/objects/attachment_tugas.php';
  
$database = new Database();
$db = $database->getConnection();
  
$attachment_tugas = new Attachment_tugas($db);
  
// get posted data
// $data = file_get_contents("php://input");
// make sure data is not empty
if(
    count($_FILES) > 0
){
  
    // set property values
    $attachment_tugas->id_tugas = $_POST['id_tugas'];
    $attachment_tugas->nama_file = $_FILES['file']['name'];
    $attachment_tugas->type = $_FILES['file']['type'];
    $attachment_tugas->attachment = $_FILES['file']["tmp_name"];
    $attachment_tugas->created_at = date('Y-m-d H:i:s');

    echo $attachment_tugas->id_tugas;
    echo $attachment_tugas->nama_file;
    echo $attachment_tugas->type;
    echo $attachment_tugas->attachment;
  
    // create the tugas
    if($attachment_tugas->insertBlob($attachment_tugas->attachment,$attachment_tugas->type,$attachment_tugas->id_tugas,$attachment_tugas->nama_file)){

        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "attachment was created."));
    }
  
    // if unable to create, tell the user
    else{
  
        // set response code - 503 service unavaiattachmentle
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create attachment."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create attachment. Data is incomplete."));
}
?>