<?php
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/hari.php';
  
// instantiate database and object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$hari = new Hari($db);

$stmt = $hari->allTrash();
$num = $stmt->rowCount();

$haris_arr=array();
$haris_arr["records"]=array();
// check if more than 0 record found
if($num>0){
  
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $hari_item=array(
            "id_hari" => $id_hari,
            "hari" => $hari,
            "id_lab" => $id_lab,
            "created_at" => $created_at,
            "updated_at" => $updated_at,
            "deleted_at" => $deleted_at,
        );
  
        array_push($haris_arr["records"], $hari_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show data in json format
    echo json_encode($haris_arr);
}
else{

    // set response code - 404 Not found
    http_response_code(200);
  
    // tell the user no record found

    echo json_encode([
        "records" => [],
        "message" => "No haris found."
    ]);
}

?>