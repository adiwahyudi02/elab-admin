<?php
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/jadwal_lab.php';
  
// instantiate database and object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$jadwal_lab = new Jadwal_lab($db);

$stmt = $jadwal_lab->allTrash();
$num = $stmt->rowCount();

$jadwal_labs_arr=array();
$jadwal_labs_arr["records"]=array();
// check if more than 0 record found
if($num>0){
  
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $jadwal_lab_item=array(
            "id_jadwal_lab" => $id_jadwal_lab,
            "id_kelas" => $id_kelas,
            "id_hari" => $id_hari,
            "jam_mulai" => $jam_mulai,
            "jam_selesai" => $jam_selesai,
            "created_at" => $created_at,
            "updated_at" => $updated_at,
            "deleted_at" => $deleted_at,
        );
  
        array_push($jadwal_labs_arr["records"], $jadwal_lab_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show data in json format
    echo json_encode($jadwal_labs_arr);
}
else{

    // set response code - 404 Not found
    http_response_code(200);
  
    // tell the user no record found

    echo json_encode([
        "records" => [],
        "message" => "No jadwal_labs found."
    ]);
}

?>