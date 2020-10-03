<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../src/config/database.php';
include_once '../src/objects/view-jadwal.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare view_jadwal object
$view_jadwal = new Jadwal($db);
  
// set ID property of record to read
$view_jadwal->id_lab = isset($_GET['id_lab']) ? $_GET['id_lab'] : die();

// read the details of view_jadwal to be edited
$view_jadwal->read();

?>