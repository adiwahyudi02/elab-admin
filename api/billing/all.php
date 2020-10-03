<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/billing.php';
  
// instantiate database and object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$billing = new Billing($db);

$stmt = $billing->all();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    $billings_arr=array();
    $billings_arr["records"]=array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $billing_item=array(
            "id_billing" => $id_billing,
            "id_komputer" => $id_komputer,
            "nama_komputer" => $nama_komputer,
            "ip_address" => $ip_address,
            "nama_lab" => $nama_lab,
            "nis" => $nis,
            "nama" => $nama,
            "id_kelas" => $id_kelas,
            "nama_kelas" => $nama_kelas,
            "date_time" => $date_time,
            "jam_keluar" => $jam_keluar,
            "created_at" => $created_at,
            "updated_at" => $updated_at
        );
  
        array_push($billings_arr["records"], $billing_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show data in json format
    echo json_encode($billings_arr);
}
else{

    // set response code - 404 Not found
    http_response_code(200);
  
    // tell the user no record found

    echo json_encode([
        "records" => [],
        "message" => "No billings found."
    ]);
}

?>