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
  
// set siswa id to be force deleted
$siswa->selected = $data->selected;

// force delete
if($siswa->multiForceDelete()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "siswa was force deleted."));
}
  
// if unable to force delete
else{
  
    // set response code - 503 service unavaisiswale
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to force delete siswa."));
}
?>