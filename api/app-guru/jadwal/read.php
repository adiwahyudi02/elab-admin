<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../../src/config/database.php';
include_once '../../src/objects/app-guru/jadwal.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare view_jadwal object
$view_jadwal = new Jadwal($db);
  
// set ID property of record to read
$view_jadwal->id_guru = isset($_GET['id_guru']) ? $_GET['id_guru'] : die();

// read the details of view_jadwal to be edited
$view_jadwal->read();

?>