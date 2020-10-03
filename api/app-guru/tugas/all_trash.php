<?php
  
// include database and object files
include_once '../../src/config/database.php';
include_once '../../src/objects/app-guru/tugas.php';
  
// instantiate database and object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$tugas = new Tugas($db);

$stmt = $tugas->allTrash();

?>