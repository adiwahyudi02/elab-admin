<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/guru.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare guru object
$guru = new Guru($db);
  
// set ID property of record to read
$guru->id_guru = isset($_GET['id_guru']) ? $_GET['id_guru'] : die();
  
// read the details of guru to be edited
$guru->read();
  
if($guru->nama_guru!=null){
    // create array
    $guru_arr = array(
        "id_guru" =>  $guru->id_guru,
        "nip" =>  $guru->nip,
        "nama_guru" => $guru->nama_guru,
        "email" =>  $guru->email,
        "username" =>  $guru->username,
        "password" =>  $guru->password,
        "created_at" => $guru->created_at,
        "updated_at" => $guru->updated_at
    );
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($guru_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user guru does not exist
    echo json_encode(array("message" => "guru does not exist."));
}
?>