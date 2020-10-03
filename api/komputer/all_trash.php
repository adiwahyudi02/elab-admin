<?php
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/komputer.php';
  
// instantiate database and object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$komputer = new Komputer($db);

$stmt = $komputer->allTrash();
$num = $stmt->rowCount();

$komputers_arr=array();
$komputers_arr["records"]=array();
// check if more than 0 record found
if($num>0){
  
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $komputer_item=array(
            "id_komputer" => $id_komputer,
            "nama_komputer" => $nama_komputer,
            "spesifikasi" => $spesifikasi,
            "nama_lab" => $nama_lab,
            "id_lab" => $id_lab,
            "ip_address" => $ip_address,
            "created_at" => $created_at,
            "updated_at" => $updated_at,
            "deleted_at" => $deleted_at,
        );
  
        array_push($komputers_arr["records"], $komputer_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show data in json format
    echo json_encode($komputers_arr);
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