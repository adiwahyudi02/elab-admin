<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/mapel.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare mapel object
$mapel = new Mapel($db);
  
// set ID property of record to read
$mapel->id_mapel = isset($_GET['id_mapel']) ? $_GET['id_mapel'] : die();
  
// read the details of mapel to be edited
$mapel->read();
  
if($mapel->nama_mapel!=null){
    // create array
    $mapel_arr = array(
        "id_mapel" =>  $mapel->id_mapel,
        "nama_mapel" => $mapel->nama_mapel,
        "created_at" => $mapel->created_at,
        "updated_at" => $mapel->updated_at
    );
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($mapel_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user mapel does not exist
    echo json_encode(array("message" => "mapel does not exist."));
}
?>