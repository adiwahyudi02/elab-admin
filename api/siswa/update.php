<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/siswa.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$siswa = new Siswa($db);
  
// get id to be edited
$data = json_decode(file_get_contents("php://input"));

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
    // set ID property to be edited
    $siswa->nis = $data->nis;

    // set property values
    $siswa->id_kelas = $data->id_kelas;
    
    // set property values
    $siswa->nama = $data->nama;

    // set property values
    $siswa->jenis_kelamin = $data->jenis_kelamin;

    // set property values
    $siswa->ttl = $data->ttl;

    // set property values
    $siswa->alamat = $data->alamat;

    // set property values
    $siswa->no_telepon = $data->no_telepon;

    // set property values
    $siswa->email = $data->email;

    // set property values
    $siswa->username = $data->username;

    // set property values
    $siswa->password = $data->password;

    // set property values
    $siswa->id_komputer = $data->id_komputer;
    $siswa->updated_at = date('Y-m-d H:i:s');
    // update
    if($siswa->update()){
    
        // set response code - 200 ok
        http_response_code(200);
    
        // tell the user
        echo json_encode(array("message" => "siswa was updated."));
    }
    
    // if unable to update, tell the user
    else{
    
        // set response code - 503 service unavaisiswale
        http_response_code(503);
    
        // tell the user
        echo json_encode(array("message" => "Unable to update siswa."));
    }
}
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create siswa. Data is incomplete."));
}
?>