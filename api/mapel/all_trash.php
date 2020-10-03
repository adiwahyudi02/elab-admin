<?php
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/mapel.php';
  
// instantiate database and object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$mapel = new Mapel($db);

$stmt = $mapel->allTrash();
$num = $stmt->rowCount();

$mapels_arr=array();
$mapels_arr["records"]=array();
// check if more than 0 record found
if($num>0){
  
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $mapel_item=array(
            "id_mapel" => $id_mapel,
            "nama_mapel" => $nama_mapel,
            "created_at" => $created_at,
            "updated_at" => $updated_at,
            "deleted_at" => $deleted_at,
        );
  
        array_push($mapels_arr["records"], $mapel_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show data in json format
    echo json_encode($mapels_arr);
}
else{

    // set response code - 404 Not found
    http_response_code(200);
  
    // tell the user no record found

    echo json_encode([
        "records" => [],
        "message" => "No mapel found."
    ]);
}

?>