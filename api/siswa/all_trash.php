<?php
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/siswa.php';
  
// instantiate database and object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$siswa = new Siswa($db);

$stmt = $siswa->allTrash();
$num = $stmt->rowCount();

$siswas_arr=array();
$siswas_arr["records"]=array();
// check if more than 0 record found
if($num>0){
  
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $siswa_item=array(
            "nis" => $nis,
            "id_kelas" => $id_kelas,
            "nama_kelas" => $nama_kelas,
            "nama" => $nama,
            "jenis_kelamin" => $jenis_kelamin,
            "ttl" => $ttl,
            "alamat" => $alamat,
            "no_telepon" => $no_telepon,
            "email" => $email,
            "username" => $username,
            "password" => $password,
            "id_komputer" => $id_komputer,
            "nama_komputer" => $nama_komputer,
            "created_at" => $created_at,
            "deleted_at" => $deleted_at,
        );
  
        array_push($siswas_arr["records"], $siswa_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show data in json format
    echo json_encode($siswas_arr);
}
else{

    // set response code - 404 Not found
    http_response_code(200);
  
    // tell the user no record found

    echo json_encode([
        "records" => [],
        "message" => "No siswas found."
    ]);
}

?>