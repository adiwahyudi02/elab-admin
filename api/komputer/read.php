<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/komputer.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare komputer object
$komputer = new Komputer($db);
  
// set ID property of record to read
$komputer->id_komputer = isset($_GET['id_komputer']) ? $_GET['id_komputer'] : die();

// read the details of komputer to be edited
$komputer->read();
  
if($komputer->nama_komputer!=null){
    // create array
    $komputer_arr = array(
        "id_komputer" =>  $komputer->id_komputer,
        "nama_komputer" => $komputer->nama_komputer,
        "spesifikasi" => $komputer->spesifikasi,
        "id_lab" => $komputer->id_lab,
        "nama_lab" => $komputer->nama_lab,
        "ip_address" => $komputer->ip_address,
        "created_at" => $komputer->created_at,
        "updated_at" => $komputer->updated_at
    );
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($komputer_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user komputer does not exist
    echo json_encode(array("message" => "komputer does not exist."));
}
?>