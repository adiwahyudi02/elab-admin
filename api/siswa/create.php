<?php
// required headers
  
// get database connection
include_once '../src/config/database.php';
  
// instantiate object
include_once '../src/objects/siswa.php';
  
$database = new Database();
$db = $database->getConnection();
  
$siswa = new Siswa($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty


if(
    !empty($data->nis) &&
    !empty($data->id_kelas) &&
    !empty($data->nama) &&
    !empty($data->jenis_kelamin) &&
    !empty($data->ttl) &&
    !empty($data->alamat) &&
    !empty($data->no_telepon) &&
    !empty($data->email) &&
    !empty($data->username) &&
    !empty($data->password) &&
    !empty($data->id_komputer)
){
    
    // set property values
    $siswa->nis = $data->nis;
    $siswa->id_kelas = $data->id_kelas;
    $siswa->nama = $data->nama;
    $siswa->jenis_kelamin = $data->jenis_kelamin;
    $siswa->ttl = $data->ttl;
    $siswa->alamat = $data->alamat;
    $siswa->no_telepon = $data->no_telepon;
    $siswa->email = $data->email;
    $siswa->username = $data->username;
    $siswa->password = $data->password;
    $siswa->id_komputer = $data->id_komputer;
    $siswa->created_at = date('Y-m-d H:i:s');
  
    // create the siswa
    if($siswa->create()){

        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "siswa was created."));
    }
  
    // if unable to create, tell the user
    else{
  
        // set response code - 503 service unavaisiswale
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create siswa."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create siswa. Data is incomplete."));
}
?>