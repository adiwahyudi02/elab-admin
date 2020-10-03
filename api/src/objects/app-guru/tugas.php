<?php
// required to decode jwt
include_once '../../src/config/core.php';
include_once '../../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../../libs/php-jwt-master/src/ExpiredException.php';
include_once '../../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../libs/php-jwt-master/src/JWT.php';
include_once '../../libs/php-jwt-master/src/JWK.php';

use \Firebase\JWT\JWT;

class Tugas{
    
    private $conn;
    private $table_name = "tugas";
  
    // object properties
    public $id_tugas;
    public $id_jadwal_mapel;
    public $title;
    public $description;
    public $due_date;
    public $status;
    public $created_at;
    public $updated_at;
    public $deleted_at;
    public $attachments;
    public $selected; //tambahan
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: POST, GET, PUT, OPTIONS, DELETE');
            header('Access-Control-Allow-Headers: token, Content-Type');
            header('Access-Control-Max-Age: 1728000');
            header('Content-Length: 0');
            header('Content-Type: text/plain');
            header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
            die();
        }
        
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $data = apache_request_headers();
        
        if (!empty($data['Authorization'])) {
            $token = $data['Authorization'];
            // get jwt
            $jwt=isset($token) ? $token : "";
            
            // if jwt is not empty
            if($jwt){
            
                // if decode succeed, show user details
                try {
                    // decode jwt
                    $decoded = JWT::decode($jwt, 'example_key', array('HS256'));
                    http_response_code(200);
                }
            
                // if decode fails, it means jwt is invalid
                catch (Exception $e){
                
                    // set response code
                    http_response_code(401);
                
                    // tell the user access denied  & show error message
                    echo json_encode(array(
                        "message" => "Access denied.",
                        "error" => $e->getMessage()
                    ));
                    exit;
                }

            }else {
                http_response_code(401);

                // tell the user access denied  & show error message
                echo json_encode(array(
                    "message" => "Access denied."
                ));
                exit;
            }
        }
        else {
            http_response_code(401);

            // tell the user access denied  & show error message
            echo json_encode(array(
                "message" => "Access denied."
            ));
            exit;
        }
    }

    function all(){
  
        // select all query
        $query = "SELECT tugas.id_tugas,jadwal_mapel.id_jadwal_mapel,tugas.title,tugas.description,tugas.due_date,tugas.status,tugas.created_at,tugas.updated_at,kelas.nama_kelas, guru.nama_guru, mapel.nama_mapel, hari.hari, lab.nama_lab
            FROM 
                tugas
            INNER JOIN 
                jadwal_mapel  
            ON 
                tugas.id_jadwal_mapel=jadwal_mapel.id_jadwal_mapel
            INNER JOIN 
                kelas  
            ON 
                jadwal_mapel.id_kelas=kelas.id_kelas
            INNER JOIN
                guru
            ON 
                jadwal_mapel.id_guru=guru.id_guru
            INNER JOIN 
                mapel  
            ON 
                jadwal_mapel.id_mapel=mapel.id_mapel
            INNER JOIN 
                jadwal_lab
            ON 
                jadwal_mapel.id_jadwal_lab=jadwal_lab.id_jadwal_lab
            INNER JOIN 
                hari
            ON 
                jadwal_lab.id_hari=hari.id_hari
            INNER JOIN 
                lab
            ON 
                hari.id_lab=lab.id_lab
            WHERE 
                tugas.deleted_at = '0000-00-00'
                ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
      
        $tugass_arr=array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $query_attach = "SELECT id_attachment, id_tugas, attachment,nama_file,type, created_at, updated_at
                FROM 
                    attachment_tugas
                WHERE 
                    id_tugas = ? &&
                    deleted_at = '0000-00-00'
                ";
            // prepare query statement
            $stmt_attach = $this->conn->prepare($query_attach);
            $stmt_attach->bindParam(1, $id_tugas);
            $stmt_attach->execute();

            $attach_arr=array();
            while ($row_attach = $stmt_attach->fetch(PDO::FETCH_ASSOC)){

                extract($row_attach);
        
                $tugas_item=array(
                    "id_attachment" => $id_attachment,
                    "id_tugas" => $id_tugas,
                    "nama_file" => $nama_file,
                    "type" => $type,
                    "attachment" => base64_encode($attachment),
                    "created_at" => $created_at,
                    "updated_at" => $updated_at
                );
        
                array_push($attach_arr, $tugas_item);
            }
            
            $tugas_item=array(
                "id_tugas" => $id_tugas,
                "id_jadwal_mapel" => $id_jadwal_mapel,
                "title" => $title,
                "description" => $description,
                "nama_kelas" => $nama_kelas,
                "nama_lab" => $nama_lab,
                "nama_guru" => $nama_guru,
                "nama_mapel" => $nama_mapel,
                "due_date" => $due_date,
                "status" => $status,
                "created_at" => $created_at,
                "updated_at" => $updated_at,
                "attachments" => $attach_arr
            );
            array_push($tugass_arr, $tugas_item);
        }

        if($tugass_arr == []){
            // set response code - 404 Not found
            http_response_code(200);
          
            // tell the user view_jadwal does not exist
            echo json_encode(array(
                "status" => false,
                "message" => "This does not exist.",
                'records' => []
            ));
        }else{
            http_response_code(200);
            echo json_encode([
                'status' => true,
                'records' => $tugass_arr
            ]);
        }
    }

    function getListTugas(){

        $query = "SELECT tugas.id_tugas,jadwal_mapel.id_jadwal_mapel,tugas.title,tugas.description,tugas.due_date,tugas.status,tugas.created_at,tugas.updated_at 
            FROM 
                jadwal_mapel 
            INNER JOIN 
                tugas 
            ON 
                tugas.id_jadwal_mapel=jadwal_mapel.id_jadwal_mapel
            WHERE 
                tugas.id_jadwal_mapel = ? &&
                tugas.deleted_at = '0000-00-00'
            ORDER BY
                tugas.created_at DESC
                ";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_jadwal_mapel);
        // execute query
        $stmt->execute();

        $tugass_arr=array();
        $tugass_arr=array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            
            $query_attach = "SELECT id_attachment, id_tugas, attachment,nama_file,type, created_at, updated_at
                FROM 
                    attachment_tugas
                WHERE 
                    id_tugas = ? &&
                    deleted_at = '0000-00-00'
                ";
            // prepare query statement
            $stmt_attach = $this->conn->prepare($query_attach);
            $stmt_attach->bindParam(1, $id_tugas);
            $stmt_attach->execute();

            $attach_arr=array();
            while ($row_attach = $stmt_attach->fetch(PDO::FETCH_ASSOC)){

                extract($row_attach);
        
                $tugas_item=array(
                    "id_attachment" => $id_attachment,
                    "id_tugas" => $id_tugas,
                    "nama_file" => $nama_file,
                    "type" => $type,
                    "attachment" => base64_encode($attachment),
                    "created_at" => $created_at,
                    "updated_at" => $updated_at
                );
        
                array_push($attach_arr, $tugas_item);
            }

            $tugas_item=array(
                "id_tugas" => $id_tugas,
                "id_jadwal_mapel" => $id_jadwal_mapel,
                "title" => $title,
                "description" => $description,
                "due_date" => $due_date,
                "status" => $status,
                "created_at" => $created_at,
                "updated_at" => $updated_at,
                "attachments" => $attach_arr
            );
    
            array_push($tugass_arr, $tugas_item);
        }
    
        // set response code - 200 OK

        $query_data = "SELECT jadwal_mapel.id_jadwal_mapel,kelas.id_kelas,kelas.nama_kelas,jadwal_lab.id_jadwal_lab,hari.hari,hari.id_hari,lab.id_lab,lab.nama_lab,mapel.id_mapel,mapel.nama_mapel,guru.id_guru,guru.nama_guru,jadwal_mapel.jam_mulai,jadwal_mapel.jam_selesai,jadwal_mapel.created_at,jadwal_mapel.updated_at 
                FROM 
                    jadwal_mapel 
                INNER JOIN 
                    kelas 
                ON 
                    jadwal_mapel.id_kelas=kelas.id_kelas 
                INNER JOIN 
                    jadwal_lab 
                ON
                    jadwal_mapel.id_jadwal_lab=jadwal_lab.id_jadwal_lab 
                INNER JOIN 
                    hari
                ON
                    jadwal_lab.id_hari=hari.id_hari
                INNER JOIN 
                    lab
                ON
                    hari.id_lab=lab.id_lab
                INNER JOIN 
                    mapel 
                ON
                    jadwal_mapel.id_mapel=mapel.id_mapel
                INNER JOIN 
                    guru 
                ON
                    jadwal_mapel.id_guru=guru.id_guru 
                WHERE
                    jadwal_mapel.id_jadwal_mapel = ? &&
                    jadwal_mapel.deleted_at = '0000-00-00'
                LIMIT
                    0,1";
    
        // prepare query_data statement
        $stmt_data = $this->conn->prepare( $query_data );
    
        // bind id of product to be updated
        $stmt_data->bindParam(1, $this->id_jadwal_mapel);
    
        // execute query
        $stmt_data->execute();
    
        // get retrieved row
        $row = $stmt_data->fetch(PDO::FETCH_ASSOC);

        if($row['id_jadwal_mapel'] == null){
            // set response code - 404 Not found
            http_response_code(200);
          
            // tell the user view_jadwal does not exist
            echo json_encode(array([
                "status" => false,
                "message" => "This does not exist."
            ]));
        }else{
            http_response_code(200);
            echo json_encode([
                'data' => [
                    'id_jadwal_mapel' => $row['id_jadwal_mapel'],
                    "id_lab" => $row['id_lab'],
                    "nama_lab" => $row['nama_lab'],
                    'id_kelas' => $row['id_kelas'],
                    'nama_kelas' => $row['nama_kelas'],
                    'id_jadwal_lab' => $row['id_jadwal_lab'],
                    'id_mapel' => $row['id_mapel'],
                    'nama_mapel' => $row['nama_mapel'],
                    'id_guru' => $row['id_guru'],
                    'nama_guru' => $row['nama_guru'],
                    'jam_mulai' => $row['jam_mulai'],
                    'jam_selesai' => $row['jam_selesai'],
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['updated_at'],
                ],
                'records' => $tugass_arr
            ]);
        }
    }

    function create($files){
  
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    id_jadwal_mapel=:id_jadwal_mapel,
                    title=:title,
                    description=:description,
                    due_date=:due_date,
                    status=:status,
                    created_at=:created_at";
      
        // prepare query
        $stmt = $this->conn->prepare($query);
      
        // sanitize
        $this->id_jadwal_mapel=htmlspecialchars(strip_tags($this->id_jadwal_mapel));
        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->due_date=htmlspecialchars(strip_tags($this->due_date));
        $this->status=htmlspecialchars(strip_tags($this->status));
        $this->created_at=htmlspecialchars(strip_tags($this->created_at));
      
        // bind values
        $stmt->bindParam(":id_jadwal_mapel", $this->id_jadwal_mapel);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":due_date", $this->due_date);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":created_at", $this->created_at);

        // execute query
        if($stmt->execute()){
            $id_tugas = $this->conn->lastInsertId();
            $total = count($files['files']['name']);

            for( $i=0 ; $i < $total ; $i++ ) {
                //Get the temp file path
                $tmpFilePath = $files['files']['tmp_name'][$i];

                if ($tmpFilePath != "") {
                    $imgData = addslashes(file_get_contents($files['files']['tmp_name'][$i]));
                    $imageProperties = getimageSize($files['files']['tmp_name'][$i]);
                    
                    $sql = "INSERT INTO attachment_tugas(id_tugas,type ,attachment, nama_file)
                    VALUES($id_tugas,'{$imageProperties['mime']}', '{$imgData}', '{$files['files']['name'][$i]}')";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->execute();
                }
            }
            return true;
        }
      
        return false;
          
    }

    function createAttachment($id_tugas, $files){

            //Get the temp file path
            $tmpFilePath = $files['files']['tmp_name'];

            if ($tmpFilePath != "") {
                $imgData = addslashes(file_get_contents($files['files']['tmp_name']));
                $imageProperties = getimageSize($files['files']['tmp_name']);
                
                $sql = "INSERT INTO attachment_tugas(id_tugas,type ,attachment, nama_file)
                VALUES($id_tugas,'{$imageProperties['mime']}', '{$imgData}', '{$files['files']['name']}')";
                $stmt = $this->conn->prepare($sql);
                if($stmt->execute()){
                    return true;
                }else{
                    return false;
                }
            }
            
    }

    function read(){

        $query_attach = "SELECT id_attachment, id_tugas, attachment,nama_file,type, created_at, updated_at
            FROM 
                attachment_tugas
            WHERE 
                id_tugas = ? &&
                deleted_at = '0000-00-00'
                ";
        // prepare query statement
        $stmt_attach = $this->conn->prepare($query_attach);
        $stmt_attach->bindParam(1, $this->id_tugas);
        $stmt_attach->execute();

        $tugass_arr=array();
        while ($row_attach = $stmt_attach->fetch(PDO::FETCH_ASSOC)){

            extract($row_attach);
    
            $tugas_item=array(
                "id_attachment" => $id_attachment,
                "id_tugas" => $id_tugas,
                "nama_file" => $nama_file,
                "type" => $type,
                "attachment" => base64_encode($attachment),
                "created_at" => $created_at,
                "updated_at" => $updated_at
            );
    
            array_push($tugass_arr, $tugas_item);
        }
        $this->attachments = $tugass_arr;
    
        // query to read single record
        $query = "SELECT tugas.id_tugas,jadwal_mapel.id_jadwal_mapel,tugas.title,tugas.description,tugas.due_date,tugas.status,tugas.created_at,tugas.updated_at 
                FROM 
                    jadwal_mapel 
                INNER JOIN 
                    tugas 
                ON 
                    tugas.id_jadwal_mapel=jadwal_mapel.id_jadwal_mapel
                WHERE
                    tugas.id_tugas = ? &&
                    tugas.deleted_at = '0000-00-00'
                LIMIT
                    0,1";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // bind id of product to be updated
        $stmt->bindParam(1, $this->id_tugas);
    
        // execute query
        $stmt->execute();
    
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // set values to object properties
        $this->id_tugas = $row['id_tugas'];
        $this->id_jadwal_mapel = $row['id_jadwal_mapel'];
        $this->title = $row['title'];
        $this->description = $row['description'];
        $this->due_date = $row['due_date'];
        $this->status = $row['status'];
        $this->created_at = $row['created_at'];
        $this->updated_at = $row['updated_at'];
    }
    
    function update(){
        // update query
        $query = "UPDATE
                " . $this->table_name . "
                SET
                    id_jadwal_mapel=:id_jadwal_mapel,
                    title=:title,
                    description=:description,
                    due_date=:due_date,
                    status=:status,
                    updated_at=:updated_at
                WHERE
                    id_tugas=:id_tugas";
      
        // prepare query statement
        $stmt = $this->conn->prepare($query);
      
        // sanitize
        $this->id_tugas=htmlspecialchars(strip_tags($this->id_tugas));
        $this->id_jadwal_mapel=htmlspecialchars(strip_tags($this->id_jadwal_mapel));
        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->due_date=htmlspecialchars(strip_tags($this->due_date));
        $this->status=htmlspecialchars(strip_tags($this->status));
        $this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
      
        // bind values
        $stmt->bindParam(":id_tugas", $this->id_tugas);
        $stmt->bindParam(":id_jadwal_mapel", $this->id_jadwal_mapel);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":due_date", $this->due_date);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":updated_at", $this->updated_at);

        // execute the query
        if($stmt->execute()){
            return true;
        }
      
        return false;
    }

    function delete(){
  
        // delete query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    deleted_at=:deleted_at
                WHERE
                    id_tugas=:id_tugas";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id_tugas=htmlspecialchars(strip_tags($this->id_tugas));
        $this->deleted_at=htmlspecialchars(strip_tags($this->deleted_at));

        // bind values
        $stmt->bindParam(":id_tugas", $this->id_tugas);
        $stmt->bindParam(":deleted_at", $this->deleted_at);

        // execute the query
        if($stmt->execute()){
            return true;
        }

        return false;
      
        return false;
    }
    function allTrash(){
  
        // select all query
        $query = "SELECT tugas.id_tugas,jadwal_mapel.id_jadwal_mapel,tugas.title,tugas.description,tugas.due_date,tugas.status,tugas.created_at,tugas.updated_at,tugas.deleted_at,kelas.nama_kelas, guru.nama_guru, mapel.nama_mapel, hari.hari, lab.nama_lab
            FROM 
                tugas
            INNER JOIN 
                jadwal_mapel  
            ON 
                tugas.id_jadwal_mapel=jadwal_mapel.id_jadwal_mapel
            INNER JOIN 
                kelas  
            ON 
                jadwal_mapel.id_kelas=kelas.id_kelas
            INNER JOIN
                guru
            ON 
                jadwal_mapel.id_guru=guru.id_guru
            INNER JOIN 
                mapel  
            ON 
                jadwal_mapel.id_mapel=mapel.id_mapel
            INNER JOIN 
                jadwal_lab
            ON 
                jadwal_mapel.id_jadwal_lab=jadwal_lab.id_jadwal_lab
            INNER JOIN 
                hari
            ON 
                jadwal_lab.id_hari=hari.id_hari
            INNER JOIN 
                lab
            ON 
                hari.id_lab=lab.id_lab
            WHERE 
                tugas.deleted_at != '0000-00-00'
                ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
      
        $tugass_arr=array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $query_attach = "SELECT id_attachment, id_tugas, attachment,nama_file,type, created_at, updated_at
                FROM 
                    attachment_tugas
                WHERE 
                    id_tugas = ? &&
                    deleted_at = '0000-00-00'
                ";
            // prepare query statement
            $stmt_attach = $this->conn->prepare($query_attach);
            $stmt_attach->bindParam(1, $id_tugas);
            $stmt_attach->execute();

            $attach_arr=array();
            while ($row_attach = $stmt_attach->fetch(PDO::FETCH_ASSOC)){

                extract($row_attach);
        
                $tugas_item=array(
                    "id_attachment" => $id_attachment,
                    "id_tugas" => $id_tugas,
                    "nama_file" => $nama_file,
                    "type" => $type,
                    "attachment" => base64_encode($attachment),
                    "created_at" => $created_at,
                    "updated_at" => $updated_at
                );
        
                array_push($attach_arr, $tugas_item);
            }
            
            $tugas_item=array(
                "id_tugas" => $id_tugas,
                "id_jadwal_mapel" => $id_jadwal_mapel,
                "title" => $title,
                "description" => $description,
                "nama_kelas" => $nama_kelas,
                "nama_lab" => $nama_lab,
                "nama_guru" => $nama_guru,
                "nama_mapel" => $nama_mapel,
                "due_date" => $due_date,
                "status" => $status,
                "created_at" => $created_at,
                "updated_at" => $updated_at,
                "deleted_at" => $deleted_at,
                "attachments" => $attach_arr
            );
            array_push($tugass_arr, $tugas_item);
        }

        if($tugass_arr == []){
            // set response code - 404 Not found
            http_response_code(200);
          
            // tell the user view_jadwal does not exist
            echo json_encode(array(
                "status" => false,
                "message" => "This does not exist.",
                'records' => []
            ));
        }else{
            http_response_code(200);
            echo json_encode([
                'status' => true,
                'records' => $tugass_arr
            ]);
        }
    }

    function multiDelete(){
        
        // delete query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    deleted_at=:deleted_at
                WHERE
                    id_tugas=:id_tugas";

        // prepare query statement
        foreach ($this->selected as $key => $value) {
            $stmt = $this->conn->prepare($query);    
            $this->id_tugas=htmlspecialchars(strip_tags($value->id_tugas));
            $this->deleted_at=htmlspecialchars(strip_tags($this->deleted_at));
            $stmt->bindParam(":id_tugas", $value->id_tugas);
            $stmt->bindParam(":deleted_at", $this->deleted_at);
            $stmt->execute();
        }
        return true;
    }

    function restore(){
  
        // restore query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    deleted_at = '0000-00-00'
                WHERE
                    id_tugas=:id_tugas";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id_tugas=htmlspecialchars(strip_tags($this->id_tugas));

        // bind values
        $stmt->bindParam(":id_tugas", $this->id_tugas);

        // execute the query
        if($stmt->execute()){
            return true;
        }

        return false;
    }


    function multiRestore(){
        // restore query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    deleted_at = '0000-00-00'
                WHERE
                    id_tugas=:id_tugas";

        // prepare query statement
        foreach ($this->selected as $key => $value) {
            $stmt = $this->conn->prepare($query);    
            $this->id_tugas=htmlspecialchars(strip_tags($value->id_tugas));
            $stmt->bindParam(":id_tugas", $value->id_tugas);
            $stmt->execute();
        }
        return true;
    }

    function forceDelete(){

        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id_tugas = ?";
  
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id_tugas));
    
        // bind id of record to delete
        $stmt->bindParam(1, $this->id_tugas);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;

    }

    function multiForceDelete(){
        // query
        $query = "DELETE FROM " . $this->table_name . " WHERE id_tugas = :id_tugas";

        // prepare query statement
        print_r($this->selected);
        foreach ($this->selected as $key => $value) {
            $stmt = $this->conn->prepare($query);    
            $this->id_tugas=htmlspecialchars(strip_tags($value->id_tugas));
            $stmt->bindParam(":id_tugas", $value->id_tugas);
            $stmt->execute();
        }
        return true;
    }
}
?>