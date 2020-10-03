<?php
// required to decode jwt
include_once '../src/config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
include_once '../libs/php-jwt-master/src/JWK.php';

use \Firebase\JWT\JWT;

class Admin{
    
    private $conn;
    private $table_name = "admin";
  
    // object properties
    public $id;
    public $nama;
    public $username;
    public $email;
    public $password;
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
                    nama=:nama,
                    username=:username,
                    email=:email,
                    password=:password,
                    created_at=:created_at";
      
        // prepare query
        $stmt = $this->conn->prepare($query);
      
        // sanitize
        $this->nama=htmlspecialchars(strip_tags($this->nama));
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->created_at=htmlspecialchars(strip_tags($this->created_at));
      
        // bind values
        $stmt->bindParam(":nama", $this->nama);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
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
                    id = ? &&
                    deleted_at = '0000-00-00'
                LIMIT
                    0,1";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // bind id of product to be updated
        $stmt->bindParam(1, $this->id);
    
        // execute query
        $stmt->execute();
    
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // set values to object properties
        $this->id = $row['id'];
        $this->nama = $row['nama'];
        $this->username = $row['username'];
        $this->email = $row['email'];
        $this->password = $row['password'];
        $this->created_at = $row['created_at'];
        $this->updated_at = $row['updated_at'];
    }
    
    function update(){
        // update query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    nama=:nama,
                    username=:username,
                    email=:email,
                    password=:password,
                    updated_at=:updated_at
                WHERE
                    id=:id";
      
        // prepare query statement
        $stmt = $this->conn->prepare($query);
      
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->nama=htmlspecialchars(strip_tags($this->nama));
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
      
        // bind values
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":nama", $this->nama);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);

        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);

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
                    id=:id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->deleted_at=htmlspecialchars(strip_tags($this->deleted_at));

        // bind values
        $stmt->bindParam(":id", $this->id);
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
                    id=:id";

        // prepare query statement
        foreach ($this->selected as $key => $value) {
            $stmt = $this->conn->prepare($query);    
            $this->id=htmlspecialchars(strip_tags($value->id));
            $this->deleted_at=htmlspecialchars(strip_tags($this->deleted_at));
            $stmt->bindParam(":id", $value->id);
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
                    id=:id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));

        // bind values
        $stmt->bindParam(":id", $this->id);

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
                    id=:id";

        // prepare query statement
        foreach ($this->selected as $key => $value) {
            $stmt = $this->conn->prepare($query);    
            $this->id=htmlspecialchars(strip_tags($value->id));
            $stmt->bindParam(":id", $value->id);
            $stmt->execute();
        }
        return true;
    }

    function forceDelete(){

        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
  
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
    
        // bind id of record to delete
        $stmt->bindParam(1, $this->id);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;

    }

    function multiForceDelete(){
        // query
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        // prepare query statement
        print_r($this->selected);
        foreach ($this->selected as $key => $value) {
            $stmt = $this->conn->prepare($query);    
            $this->id=htmlspecialchars(strip_tags($value->id));
            $stmt->bindParam(":id", $value->id);
            $stmt->execute();
        }
        return true;
    }
}
?>