<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/hari.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare hari object
$hari = new Hari($db);
  
// set ID property of record to read
$hari->id_hari = isset($_GET['id_hari']) ? $_GET['id_hari'] : die();
  
// read the details of hari to be edited
$hari->read();
  
if($hari->hari!=null){
    // create array
    $hari_arr = array(
        "id_hari" =>  $hari->id_hari,
        "hari" => $hari->hari,
        "id_lab" => $hari->id_lab,
        "created_at" => $hari->created_at,
        "updated_at" => $hari->updated_at
    );
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($hari_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user hari does not exist
    echo json_encode(array("message" => "hari does not exist."));
}
?>