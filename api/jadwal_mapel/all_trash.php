<?php
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/jadwal_mapel.php';
  
// instantiate database and object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$jadwal_mapel = new jadwal_mapel($db);

$stmt = $jadwal_mapel->allTrash();
$num = $stmt->rowCount();

$jadwal_mapels_arr=array();
$jadwal_mapels_arr["records"]=array();
// check if more than 0 record found
if($num>0){
  
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $jadwal_mapel_item=array(
            "id_jadwal_mapel" => $id_jadwal_mapel,
            "id_kelas" => $id_kelas,
            "id_jadwal_lab" => $id_jadwal_lab,
            "id_mapel" => $id_mapel,
            "id_guru" => $id_jadwal_mapel,
            "jam_mulai" => $jam_mulai,
            "jam_selesai" => $jam_selesai,
            "created_at" => $created_at,
            "updated_at" => $updated_at
        );
  
        array_push($jadwal_mapels_arr["records"], $jadwal_mapel_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show data in json format
    echo json_encode($jadwal_mapels_arr);
}
else{

    // set response code - 404 Not found
    http_response_code(200);
  
    // tell the user no record found

    echo json_encode([
        "records" => [],
        "message" => "No jadwal_mapels found."
    ]);
}

?>