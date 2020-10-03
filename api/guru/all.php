<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/guru.php';
  
// instantiate database and object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$guru = new Guru($db);

$stmt = $guru->all();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    $gurus_arr=array();
    $gurus_arr["records"]=array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $guru_item=array(
            "id_guru" => $id_guru,
            "nip" => $nip,
            "nama_guru" => $nama_guru,
            "email" => $email,
            "username" => $username,
            "password" => $password,
            "created_at" => $created_at,
            "updated_at" => $updated_at
        );
  
        array_push($gurus_arr["records"], $guru_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show data in json format
    echo json_encode($gurus_arr);
}
else{

    // set response code - 404 Not found
    http_response_code(200);
  
    // tell the user no record found

    echo json_encode([
        "records" => [],
        "message" => "No gurus found."
    ]);
}

?>