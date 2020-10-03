<?php
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/lab.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare lab object
$lab = new Lab($db);
  
// set ID property of record to read
$lab->id_lab = isset($_GET['id_lab']) ? $_GET['id_lab'] : die();
  
// read the details of lab to be edited
$lab->read();
  
if($lab->nama_lab!=null){
    // create array
    $lab_arr = array(
        "id_lab" =>  $lab->id_lab,
        "nama_lab" => $lab->nama_lab,
        "created_at" => $lab->created_at,
        "updated_at" => $lab->updated_at
    );
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($lab_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user lab does not exist
    echo json_encode(array("message" => "lab does not exist."));
}
?>