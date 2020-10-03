<?php
// required to decode jwt
include_once '../src/config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
include_once '../libs/php-jwt-master/src/JWK.php';

use \Firebase\JWT\JWT;

class Kelas{
    
    private $conn;
    private $table_name = "kelas";
  
    // object properties
    public $id_kelas;
    public $nama_kelas;
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
        $query = "SELECT * FROM 
                    " . $this->table_name . "
                WHERE
                    deleted_at = '0000-00-00'
                ";
      
        // prepare query statement
        $stmt = $this->conn->prepare($query);
      
        // execute query
        $stmt->execute();
      
        return $stmt;
    }

    function create(){
  
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    nama_kelas=:nama_kelas,
                    created_at=:created_at";
      
        // prepare query
        $stmt = $this->conn->prepare($query);
      
        // sanitize
        $this->nama_kelas=htmlspecialchars(strip_tags($this->nama_kelas));
        $this->created_at=htmlspecialchars(strip_tags($this->created_at));
      
        // bind values
        $stmt->bindParam(":nama_kelas", $this->nama_kelas);
        $stmt->bindParam(":created_at", $this->created_at);
      
        // execute query
        if($stmt->execute()){
            return true;
        }
      
        return false;
          
    }

    function read(){
    
        // query to read single record
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . "
                WHERE
                    id_kelas = ? &&
                    deleted_at = '0000-00-00'
                LIMIT
                    0,1";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // bind id of product to be updated
        $stmt->bindParam(1, $this->id_kelas);
    
        // execute query
        $stmt->execute();
    
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // set values to object properties
        $this->id_kelas = $row['id_kelas'];
        $this->nama_kelas = $row['nama_kelas'];
        $this->created_at = $row['created_at'];
        $this->updated_at = $row['updated_at'];
    }
    
    function update(){
        // update query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    nama_kelas=:nama_kelas,
                    updated_at=:updated_at
                WHERE
                    id_kelas=:id_kelas";
      
        // prepare query statement
        $stmt = $this->conn->prepare($query);
      
        // sanitize
        $this->id_kelas=htmlspecialchars(strip_tags($this->id_kelas));
        $this->nama_kelas=htmlspecialchars(strip_tags($this->nama_kelas));
        $this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
      
        // bind values
        $stmt->bindParam(":id_kelas", $this->id_kelas);
        $stmt->bindParam(":nama_kelas", $this->nama_kelas);
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
                    id_kelas=:id_kelas";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id_kelas=htmlspecialchars(strip_tags($this->id_kelas));
        $this->deleted_at=htmlspecialchars(strip_tags($this->deleted_at));

        // bind values
        $stmt->bindParam(":id_kelas", $this->id_kelas);
        $stmt->bindParam(":deleted_at", $this->deleted_at);

        // execute the query
        if($stmt->execute()){
            return true;
        }
      
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
                    id_kelas=:id_kelas";

        // prepare query statement
        foreach ($this->selected as $key => $value) {
            $stmt = $this->conn->prepare($query);    
            $this->id_kelas=htmlspecialchars(strip_tags($value->id_kelas));
            $this->deleted_at=htmlspecialchars(strip_tags($this->deleted_at));
            $stmt->bindParam(":id_kelas", $value->id_kelas);
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
                    id_kelas=:id_kelas";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id_kelas=htmlspecialchars(strip_tags($this->id_kelas));

        // bind values
        $stmt->bindParam(":id_kelas", $this->id_kelas);

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
                    id_kelas=:id_kelas";

        // prepare query statement
        foreach ($this->selected as $key => $value) {
            $stmt = $this->conn->prepare($query);    
            $this->id_kelas=htmlspecialchars(strip_tags($value->id_kelas));
            $stmt->bindParam(":id_kelas", $value->id_kelas);
            $stmt->execute();
        }
        return true;
    }

    function forceDelete(){

        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id_kelas = ?";
  
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id_kelas));
    
        // bind id of record to delete
        $stmt->bindParam(1, $this->id_kelas);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;

    }

    function multiForceDelete(){
        // query
        $query = "DELETE FROM " . $this->table_name . " WHERE id_kelas = :id_kelas";

        // prepare query statement
        print_r($this->selected);
        foreach ($this->selected as $key => $value) {
            $stmt = $this->conn->prepare($query);    
            $this->id_kelas=htmlspecialchars(strip_tags($value->id_kelas));
            $stmt->bindParam(":id_kelas", $value->id_kelas);
            $stmt->execute();
        }
        return true;
    }
}
?>