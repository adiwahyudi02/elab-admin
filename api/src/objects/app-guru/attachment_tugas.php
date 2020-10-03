<?php
// required to decode jwt
include_once '../../src/config/core.php';
include_once '../../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../../libs/php-jwt-master/src/ExpiredException.php';
include_once '../../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../libs/php-jwt-master/src/JWT.php';
include_once '../../libs/php-jwt-master/src/JWK.php';

use \Firebase\JWT\JWT;

class Attachment_tugas{
    
    private $conn;
    private $table_name = "attachment_tugas";
  
    // object properties
    public $id_attachment;
    public $id_tugas;
    public $nama_file;
    public $attachment;
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
        $query = "SELECT attachment_tugas.id_attachment_tugas,tugas.id_tugas,attachment_tugas.nama_file,attachment_tugas.attachment,attachment_tugas.created_at,attachment_tugas.updated_at 
            FROM 
                tugas 
            INNER JOIN 
                attachment_tugas 
            ON 
                attachment_tugas.id_tugas=tugas.id_tugas
            WHERE 
                attachment_tugas.deleted_at = '0000-00-00'
                ";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
      
        // execute query
        $stmt->execute();
      
        return $stmt;
    }
    function insertBlob($filePath, $mime,$id_tugas,$nama_file) {
        $blob = fopen($filePath, 'rb');

        $sql = "INSERT INTO attachment_tugas(type,attachment,id_tugas,nama_file) VALUES(:mime,:data,:id_tugas,:nama_file)";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':mime', $mime);
        $stmt->bindParam(':id_tugas', $id_tugas);
        $stmt->bindParam(':nama_file', $nama_file);
        $stmt->bindParam(':data', $blob, PDO::PARAM_LOB);

        return $stmt->execute();
    }
    // function create(){
  
    //     // query to insert record
    //     $query = "INSERT INTO
    //                 " . $this->table_name . "
    //             SET
    //                 id_jadwal_mapel=:id_jadwal_mapel,
    //                 title=:title,
    //                 description=:description,
    //                 due_date=:due_date,
    //                 status=:status,
    //                 created_at=:created_at";
      
    //     // prepare query
    //     $stmt = $this->conn->prepare($query);
      
    //     // sanitize
    //     $this->id_jadwal_mapel=htmlspecialchars(strip_tags($this->id_jadwal_mapel));
    //     $this->title=htmlspecialchars(strip_tags($this->title));
    //     $this->description=htmlspecialchars(strip_tags($this->description));
    //     $this->due_date=htmlspecialchars(strip_tags($this->due_date));
    //     $this->status=htmlspecialchars(strip_tags($this->status));
    //     $this->created_at=htmlspecialchars(strip_tags($this->created_at));
      
    //     // bind values
    //     $stmt->bindParam(":id_jadwal_mapel", $this->id_jadwal_mapel);
    //     $stmt->bindParam(":title", $this->title);
    //     $stmt->bindParam(":description", $this->description);
    //     $stmt->bindParam(":due_date", $this->due_date);
    //     $stmt->bindParam(":status", $this->status);
    //     $stmt->bindParam(":created_at", $this->created_at);
      
    //     // execute query
    //     if($stmt->execute()){
    //         return true;
    //     }
      
    //     return false;
          
    // }

    function read(){
    
        // query to read single record
        $query = "SELECT attachment_tugas.id_attachment_tugas,tugas.id_tugas,tugas.status,attachment_tugas.nama_file,attachment_tugas.attachment,attachment_tugas.created_at,attachment_tugas.updated_at 
                FROM 
                    tugas 
                INNER JOIN 
                    attachment_tugas 
                ON 
                    attachment_tugas.id_tugas=tugas.id_tugas
                WHERE
                    attachment_tugas.id_attachment_tugas = ? &&
                    attachment_tugas.deleted_at = '0000-00-00'
                LIMIT
                    0,1";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // bind id of product to be updated
        $stmt->bindParam(1, $this->id_attachment_tugas);
    
        // execute query
        $stmt->execute();
    
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // set values to object properties
        $this->id_attachment_tugas = $row['id_attachment_tugas'];
        $this->id_tugas = $row['id_tugas'];
        $this->nama_file = $row['nama_file'];
        $this->attachment = base64_encode($row['attachment']);
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
                    id_attachment=:id_attachment";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id_attachment=htmlspecialchars(strip_tags($this->id_attachment));
        $this->deleted_at=htmlspecialchars(strip_tags($this->deleted_at));

        // bind values
        $stmt->bindParam(":id_attachment", $this->id_attachment);
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