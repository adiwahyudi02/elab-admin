<?php
// required to decode jwt
include_once '../../src/config/core.php';
include_once '../../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../../libs/php-jwt-master/src/ExpiredException.php';
include_once '../../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../libs/php-jwt-master/src/JWT.php';
include_once '../../libs/php-jwt-master/src/JWK.php';

use \Firebase\JWT\JWT;

class Work{
    
    private $conn;
    private $table_name = "work";
  
    // object properties
    public $id_work;
    public $nis;
    public $nama;
    public $id_tugas;
    public $status;
    public $created_at;
    public $updated_at;
    public $deleted_at;
  
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
        $query = "SELECT work.id_work,siswa.nis,siswa.nama,tugas.id_tugas,work.status,work.created_at,work.updated_at 
            FROM 
                work 
            INNER JOIN 
                siswa 
            ON 
                work.nis=siswa.nis 
            INNER JOIN 
                tugas 
            ON 
                work.id_work=tugas.id_tugas
            WHERE 
                work.deleted_at = '0000-00-00'
                ";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
      
        // execute query
        $stmt->execute();
      
        return $stmt;
    }

    function getListWork(){
        $query = "SELECT work.id_work,siswa.nis,siswa.nama,tugas.id_tugas,work.status,work.created_at,work.updated_at,work.date_time
            FROM 
                work
            INNER JOIN 
                siswa 
            ON 
                work.nis=siswa.nis 
            INNER JOIN 
                tugas 
            ON 
                work.id_work=tugas.id_tugas
            WHERE 
                work.id_tugas = ? &&
                work.deleted_at = '0000-00-00'
            ORDER BY
                work.date_time
                ";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_tugas);
        // execute query
        $stmt->execute();

        $tugass_arr=array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

            extract($row);

            $query_attach = "SELECT id_attachment_work, id_work, attachment,nama_file,type, created_at, updated_at
            FROM 
                attachment_work
            WHERE 
                id_work = ? &&
                deleted_at = '0000-00-00'
                ";
            // prepare query statement
            $stmt_attach = $this->conn->prepare($query_attach);
            $stmt_attach->bindParam(1, $id_work);
            $stmt_attach->execute();

            $work_arr=array();
            while ($row_attach = $stmt_attach->fetch(PDO::FETCH_ASSOC)){

                extract($row_attach);
        
                $tugas_item=array(
                    "id_attachment" => $id_attachment_work,
                    "id_work" => $id_work,
                    "nama_file" => $nama_file,
                    "type" => $type,
                    "attachment" => base64_encode($attachment),
                    "created_at" => $created_at,
                    "updated_at" => $updated_at
                );
        
                array_push($work_arr, $tugas_item);
            }

            $tugas_item=array(
                "id_work" => $id_work,
                "nis" => $nis,
                "nama" => $nama,
                "id_tugas" => $id_tugas,
                "status" => $status,
                "date_time" => $date_time,
                "created_at" => $created_at,
                "updated_at" => $updated_at,
                "attachments" => $work_arr
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

    function create(){
  
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    nis=:nis,
                    id_tugas=:id_tugas,
                    status=:status,
                    created_at=:created_at";
      
        // prepare query
        $stmt = $this->conn->prepare($query);
      
        // sanitize
        $this->nis=htmlspecialchars(strip_tags($this->nis));
        $this->id_tugas=htmlspecialchars(strip_tags($this->id_tugas));
        $this->status=htmlspecialchars(strip_tags($this->status));
        $this->created_at=htmlspecialchars(strip_tags($this->created_at));
      
        // bind values
        $stmt->bindParam(":nis", $this->nis);
        $stmt->bindParam(":id_tugas", $this->id_tugas);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":created_at", $this->created_at);
      
        // execute query
        if($stmt->execute()){
            return true;
        }
      
        return false;
          
    }

    function read(){
    
        // query to read single record
        $query = "SELECT work.id_work,siswa.nis,siswa.nama,tugas.id_tugas,work.status,work.created_at,work.updated_at 
                FROM 
                    siswa 
                INNER JOIN 
                    work 
                ON 
                    work.nis=siswa.nis 
                INNER JOIN 
                    tugas 
                ON 
                    work.id_work=tugas.id_tugas
                WHERE
                    work.id_work = ? &&
                    work.deleted_at = '0000-00-00'
                LIMIT
                    0,1";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // bind id of product to be updated
        $stmt->bindParam(1, $this->id_work);
    
        // execute query
        $stmt->execute();
    
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // set values to object properties
        $this->id_work = $row['id_work'];
        $this->nis = $row['nis'];
        $this->nama = $row['nama'];
        $this->id_tugas = $row['id_tugas'];
        $this->status = $row['status'];
        $this->created_at = $row['created_at'];
        $this->updated_at = $row['updated_at'];
    }
    
    function update(){
        // update query
        $query = "UPDATE
                " . $this->table_name . "
                SET
                    nis=:nis,
                    id_tugas=:id_tugas,
                    status=:status,
                    updated_at=:updated_at
                WHERE
                    id_work=:id_work";
      
        // prepare query statement
        $stmt = $this->conn->prepare($query);
      
        // sanitize
        $this->id_work=htmlspecialchars(strip_tags($this->id_work));
        $this->nis=htmlspecialchars(strip_tags($this->nis));
        $this->id_tugas=htmlspecialchars(strip_tags($this->id_tugas));
        $this->status=htmlspecialchars(strip_tags($this->status));
        $this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
      
        // bind values
        $stmt->bindParam(":id_work", $this->id_work);
        $stmt->bindParam(":nis", $this->nis);
        $stmt->bindParam(":id_tugas", $this->id_tugas);
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
                    id_work=:id_work";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id_work=htmlspecialchars(strip_tags($this->id_work));
        $this->deleted_at=htmlspecialchars(strip_tags($this->deleted_at));

        // bind values
        $stmt->bindParam(":id_work", $this->id_work);
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
        $query = "SELECT * FROM 
                    " . $this->table_name . "
                WHERE
                    deleted_at != '0000-00-00'
                ";
      
        // prepare query statement
        $stmt = $this->conn->prepare($query);
      
        // execute query
        $stmt->execute();
      
        return $stmt;
    }

    function multiDelete(){
        
        // delete query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    deleted_at=:deleted_at
                WHERE
                    id_work=:id_work";

        // prepare query statement
        foreach ($this->selected as $key => $value) {
            $stmt = $this->conn->prepare($query);    
            $this->id_work=htmlspecialchars(strip_tags($value->id_work));
            $this->deleted_at=htmlspecialchars(strip_tags($this->deleted_at));
            $stmt->bindParam(":id_work", $value->id_work);
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
                    id_work=:id_work";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id_work=htmlspecialchars(strip_tags($this->id_work));

        // bind values
        $stmt->bindParam(":id_work", $this->id_work);

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
                    id_work=:id_work";

        // prepare query statement
        foreach ($this->selected as $key => $value) {
            $stmt = $this->conn->prepare($query);    
            $this->id_work=htmlspecialchars(strip_tags($value->id_work));
            $stmt->bindParam(":id_work", $value->id_work);
            $stmt->execute();
        }
        return true;
    }

    function forceDelete(){

        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id_work = ?";
  
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id_work));
    
        // bind id of record to delete
        $stmt->bindParam(1, $this->id_work);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;

    }

    function multiForceDelete(){
        // query
        $query = "DELETE FROM " . $this->table_name . " WHERE id_work = :id_work";

        // prepare query statement
        print_r($this->selected);
        foreach ($this->selected as $key => $value) {
            $stmt = $this->conn->prepare($query);    
            $this->id_work=htmlspecialchars(strip_tags($value->id_work));
            $stmt->bindParam(":id_work", $value->id_work);
            $stmt->execute();
        }
        return true;
    }
}
?>