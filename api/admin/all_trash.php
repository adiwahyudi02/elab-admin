<?php
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/admin.php';
  
// instantiate database and object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$admin = new Admin($db);

$stmt = $admin->allTrash();
$num = $stmt->rowCount();

$admins_arr=array();
$admins_arr["records"]=array();
// check if more than 0 record found
if($num>0){
  
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $admin_item=array(
            "id" => $id,
            "nama" => $nama,
            "username" => $username,
            "email" => $email,
            "password" => $password,
            "created_at" => $created_at,
            "updated_at" => $updated_at,
            "deleted_at" => $deleted_at,
        );
  
        array_push($admins_arr["records"], $admin_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show data in json format
    echo json_encode($admins_arr);

}
else{

    // set response code - 404 Not found
    http_response_code(200);
  
    // tell the user no record found

    echo json_encode([
        "records" => [],
        "message" => "No admins found."
    ]);
}

?>