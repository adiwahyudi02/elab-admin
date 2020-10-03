<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/siswa.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare siswa object
$siswa = new Siswa($db);
  
// set ID property of record to read
$siswa->nis = isset($_GET['nis']) ? $_GET['nis'] : die();

// read the details of siswa to be edited
$siswa->read();
  
if($siswa->nama!=null){
    // create array
    $siswa_arr = array(
        "nis" =>  $siswa->nis,
        "id_kelas" => $siswa->id_kelas,
        "nama" => $siswa->nama,
        "jenis_kelamin" => $siswa->jenis_kelamin,
        "ttl" => $siswa->ttl,
        "alamat" => $siswa->alamat,
        "no_telepon" => $siswa->no_telepon,
        "email" => $siswa->email,
        "username" => $siswa->username,
        "password" => $siswa->password,
        "id_komputer" => $siswa->id_komputer,
        "created_at" => $siswa->created_at,
        "updated_at" => $siswa->updated_at
    );
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($siswa_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user siswa does not exist
    echo json_encode(array("message" => "siswa does not exist."));
}
?>