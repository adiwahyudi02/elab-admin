<?php
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/lab.php';
  
// instantiate database and object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$lab = new Lab($db);

$stmt = $lab->allTrash();
$num = $stmt->rowCount();

$labs_arr=array();
$labs_arr["records"]=array();
// check if more than 0 record found
if($num>0){
  
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $lab_item=array(
            "id_lab" => $id_lab,
            "nama_lab" => $nama_lab,
            "created_at" => $created_at,
            "updated_at" => $updated_at,
            "deleted_at" => $deleted_at,
        );
  
        array_push($labs_arr["records"], $lab_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show data in json format
    echo json_encode($labs_arr);
}
else{

    // set response code - 404 Not found
    http_response_code(200);
  
    // tell the user no record found

    echo json_encode([
        "records" => [],
        "message" => "No labs found."
    ]);
}

?>