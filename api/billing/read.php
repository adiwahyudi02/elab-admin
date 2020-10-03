<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/billing.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare billing object
$billing = new billing($db);
  
// set ID property of record to read
$billing->id_billing = isset($_GET['id_billing']) ? $_GET['id_billing'] : die();
  
// read the details of billing to be edited
$billing->read();
  
if($billing->date_time!=null){
    // create array
    $billing_arr = array(
        "id_billing" =>  $billing->id_billing,
        "id_komputer" =>  $billing->id_komputer,
        "nis" =>  $billing->nis,
        "date_time" =>  $billing->date_time,
        "id_mapel" => $billing->id_mapel,
        "id_guru" => $billing->id_guru,
        "created_at" => $billing->created_at,
        "updated_at" => $billing->updated_at
    );
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($billing_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user billing does not exist
    echo json_encode(array("message" => "billing does not exist."));
}
?>