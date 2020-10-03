<?php
// required to decode jwt
include_once '../src/config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
include_once '../libs/php-jwt-master/src/JWK.php';

use \Firebase\JWT\JWT;

class Komputer{
    
    private $conn;
    private $table_name = "komputer";
  
    // object properties
    public $id_komputer;
    public $nama_komputer;
    public $spesifikasi;
    public $id_lab;
    public $ip_address;
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
        $query = "SELECT komputer.id_komputer,komputer.nama_komputer,komputer.spesifikasi,lab.id_lab,lab.nama_lab,komputer.ip_address,komputer.created_at,komputer.updated_at,komputer.deleted_at 
            FROM 
                komputer 
            INNER JOIN 
                lab 
            ON 
                lab.id_lab = komputer.id_lab 
            WHERE 
                komputer.deleted_at = '0000-00-00'
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
                    nama_komputer=:nama_komputer,
                    spesifikasi=:spesifikasi,
                    id_lab=:id_lab,
                    ip_address=:ip_address,
                    created_at=:created_at";
      
        // prepare query
        $stmt = $this->conn->prepare($query);
      
        // sanitize
        $this->nama_komputer=htmlspecialchars(strip_tags($this->nama_komputer));
        $this->spesifikasi=htmlspecialchars(strip_tags($this->spesifikasi));
        $this->id_lab=htmlspecialchars(strip_tags($this->id_lab));
        $this->ip_address=htmlspecialchars(strip_tags($this->ip_address));
        $this->created_at=htmlspecialchars(strip_tags($this->created_at));
      
        // bind values
        $stmt->bindParam(":nama_komputer", $this->nama_komputer);
        $stmt->bindParam(":spesifikasi", $this->spesifikasi);
        $stmt->bindParam(":id_lab", $this->id_lab);
        $stmt->bindParam(":ip_address", $this->ip_address);
        $stmt->bindParam(":created_at", $this->created_at);
      
        // execute query
        if($stmt->execute()){
            return true;
        }
      
        return false;
          
    }

    function read(){
    
        // query to read single record
        $query = "SELECT komputer.id_komputer,komputer.id_lab,komputer.nama_komputer,komputer.spesifikasi,lab.id_lab,lab.nama_lab,komputer.ip_address,komputer.created_at,komputer.updated_at,komputer.deleted_at 
                FROM 
                    komputer 
                INNER JOIN 
                    lab 
                ON 
                    lab.id_lab = komputer.id_lab 
                WHERE
                    komputer.id_komputer = ? &&
                    komputer.deleted_at = '0000-00-00'
                LIMIT
                    0,1";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // bind id of product to be updated
        $stmt->bindParam(1, $this->id_komputer);
    
        // execute query
        $stmt->execute();
    
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // set values to object properties
        $this->id_komputer = $row['id_komputer'];
        $this->nama_komputer = $row['nama_komputer'];
        $this->spesifikasi = $row['spesifikasi'];
        $this->id_lab = $row['id_lab'];
        $this->nama_lab = $row['nama_lab'];
        $this->ip_address = $row['ip_address'];
        $this->created_at = $row['created_at'];
        $this->updated_at = $row['updated_at'];
    }
    
    function update(){
        // update query
        $query = "UPDATE
                " . $this->table_name . "
                SET
                    nama_komputer=:nama_komputer,
                    spesifikasi=:spesifikasi,
                    id_lab=:id_lab,
                    ip_address=:ip_address,
                    updated_at=:updated_at
                WHERE
                    id_komputer=:id_komputer";
      
        // prepare query statement
        $stmt = $this->conn->prepare($query);
      
        // sanitize
        $this->id_komputer=htmlspecialchars(strip_tags($this->id_komputer));
        $this->nama_komputer=htmlspecialchars(strip_tags($this->nama_komputer));
        $this->spesifikasi=htmlspecialchars(strip_tags($this->spesifikasi));
        $this->id_lab=htmlspecialchars(strip_tags($this->id_lab));
        $this->ip_address=htmlspecialchars(strip_tags($this->ip_address));
        $this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
      
        // bind values
        $stmt->bindParam(":id_komputer", $this->id_komputer);
        $stmt->bindParam(":nama_komputer", $this->nama_komputer);
        $stmt->bindParam(":spesifikasi", $this->spesifikasi);
        $stmt->bindParam(":id_lab", $this->id_lab);
        $stmt->bindParam(":ip_address", $this->ip_address);
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
                    id_komputer=:id_komputer";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id_komputer=htmlspecialchars(strip_tags($this->id_komputer));
        $this->deleted_at=htmlspecialchars(strip_tags($this->deleted_at));

        // bind values
        $stmt->bindParam(":id_komputer", $this->id_komputer);
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
        $query = "SELECT komputer.id_komputer,komputer.nama_komputer,komputer.spesifikasi,lab.id_lab,lab.nama_lab,komputer.ip_address,komputer.created_at,komputer.updated_at,komputer.deleted_at 
                FROM 
                    komputer 
                INNER JOIN 
                    lab 
                ON 
                    lab.id_lab = komputer.id_lab 
                WHERE
                    komputer.deleted_at != '0000-00-00'
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
                    id_komputer=:id_komputer";

        // prepare query statement
        foreach ($this->selected as $key => $value) {
            $stmt = $this->conn->prepare($query);    
            $this->id_komputer=htmlspecialchars(strip_tags($value->id_komputer));
            $this->deleted_at=htmlspecialchars(strip_tags($this->deleted_at));
            $stmt->bindParam(":id_komputer", $value->id_komputer);
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
                    id_komputer=:id_komputer";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id_komputer=htmlspecialchars(strip_tags($this->id_komputer));

        // bind values
        $stmt->bindParam(":id_komputer", $this->id_komputer);

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
                    id_komputer=:id_komputer";

        // prepare query statement
        foreach ($this->selected as $key => $value) {
            $stmt = $this->conn->prepare($query);    
            $this->id_komputer=htmlspecialchars(strip_tags($value->id_komputer));
            $stmt->bindParam(":id_komputer", $value->id_komputer);
            $stmt->execute();
        }
        return true;
    }

    function forceDelete(){

        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id_komputer = ?";
  
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id_komputer));
    
        // bind id of record to delete
        $stmt->bindParam(1, $this->id_komputer);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;

    }

    function multiForceDelete(){
        // query
        $query = "DELETE FROM " . $this->table_name . " WHERE id_komputer = :id_komputer";

        // prepare query statement
        print_r($this->selected);
        foreach ($this->selected as $key => $value) {
            $stmt = $this->conn->prepare($query);    
            $this->id_komputer=htmlspecialchars(strip_tags($value->id_komputer));
            $stmt->bindParam(":id_komputer", $value->id_komputer);
            $stmt->execute();
        }
        return true;
    }
}
?>