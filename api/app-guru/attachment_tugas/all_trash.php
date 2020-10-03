<?php
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/attachment_tugas.php';
  
// instantiate database and object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$attachment_tugas = new attachment_tugas($db);

$stmt = $attachment_tugas->allTrash();
$num = $stmt->rowCount();

$attachment_tugass_arr=array();
$attachment_tugass_arr["records"]=array();
// check if more than 0 record found
if($num>0){
  
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $attachment_tugas_item=array(
            "id_attachment_tugas" => $id_attachment_tugas,
            "id_tugas" => $id_tugas,
            "nama_file" => $nama_file,
            "attachment" => $attachment,
            "created_at" => $created_at,
            "updated_at" => $updated_at
        );
  
        array_push($attachment_tugass_arr["records"], $attachment_tugas_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show data in json format
    echo json_encode($attachment_tugass_arr);
}
else{

    // set response code - 404 Not found
    http_response_code(200);
  
    // tell the user no record found

    echo json_encode([
        "records" => [],
        "message" => "No attachment_tugass found."
    ]);
}

?>