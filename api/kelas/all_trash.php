<?php
// instantiate database and object
include_once '../src/config/database.php';
include_once '../src/objects/kelas.php';

$database = new Database();
$db = $database->getConnection();
  
// initialize object
$kelas = new Kelas($db);

$stmt = $kelas->allTrash();
$num = $stmt->rowCount();

$kelass_arr=array();
$kelass_arr["records"]=array();
// check if more than 0 record found
if($num>0){
  
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $kelas_item=array(
            "id_kelas" => $id_kelas,
            "nama_kelas" => $nama_kelas,
            "created_at" => $created_at,
            "updated_at" => $updated_at,
            "deleted_at" => $deleted_at,
        );
  
        array_push($kelass_arr["records"], $kelas_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show data in json format
    echo json_encode($kelass_arr);
}
else{

    // set response code - 404 Not found
    http_response_code(200);
  
    // tell the user no record found

    echo json_encode([
        "records" => [],
        "message" => "No kelass found."
    ]);
}

?>