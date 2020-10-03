<?php
  
// include database and object file
include_once '../src/config/database.php';
include_once '../src/objects/siswa.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$siswa = new Siswa($db);

// get id
$data = json_decode(file_get_contents("php://input"));
  
// set siswa id to be deleted
$siswa->selected = $data->selected;

$siswa->deleted_at = date('Y-m-d H:i:s');

// delete
if($siswa->multiDelete()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "siswa was deleted."));
}
  
// if unable to delete
else{
  
    // set response code - 503 service unavaisiswale
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to delete siswa."));
}
?>