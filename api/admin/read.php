<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/admin.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare admin object
$admin = new Admin($db);
  
// set ID property of record to read
$admin->id = isset($_GET['id']) ? $_GET['id'] : die();
  
// read the details of admin to be edited
$admin->read();
  
if($admin->nama!=null){
    // create array
    $admin_arr = array(
        "id" =>  $admin->id,
        "nama" => $admin->nama,
        "username" => $admin->username,
        "email" => $admin->email,
        "password" => $admin->password,
        "created_at" => $admin->created_at,
        "updated_at" => $admin->updated_at
    );
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($admin_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user admin does not exist
    echo json_encode(array("message" => "admin does not exist."));
}
?>