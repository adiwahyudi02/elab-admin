<?php


// include database and object file
include_once '../src/config/database.php';
include_once '../src/objects/tugas.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$tugas = new Tugas($db);

// get id
$data = json_decode(file_get_contents("php://input"));
  
// set tugas id to be restored
$tugas->id_tugas = $data->id_tugas;

// restore
if($tugas->restore()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "tugas was restored."));
}
  
// if unable to restore
else{
  
    // set response code - 503 service unavaitugasle
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to restore tugas."));
}
?>
 "id_attachment_tugas" =>  $attachment_tugas->id_attachment_tugas,
        "id_tugas" => $attachment_tugas->id_tugas,
        "nama_file" =>  $attachment_tugas->nama_file,
        "attachment" => $attachment_tugas->attachment,
        "created_at" => $attachment_tugas->created_at,
        "updated_at" => $attachment_tugas->updated_at