<?php
// required to decode jwt
include_once '../src/config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
include_once '../libs/php-jwt-master/src/JWK.php';

use \Firebase\JWT\JWT;

class Siswa{
    
    private $conn;
    private $table_name = "siswa";
  
    // object properties
    public $nis;
    public $id_kelas;
    public $nama_kelas;
    public $nama;
    public $jenis_kelamin;
    public $ttl;
    public $alamat;
    public $no_telepon;
    public $email;
    public $username;
    public $password;
    public $id_komputer;
    public $nama_komputer;
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
        $query = "SELECT siswa.nis,kelas.id_kelas,kelas.nama_kelas,siswa.nama,siswa.jenis_kelamin,siswa.ttl,siswa.alamat,siswa.no_telepon,siswa.email,siswa.username,siswa.password,komputer.id_komputer,komputer.nama_komputer,siswa.created_at,siswa.updated_at
            FROM 
                kelas 
            INNER JOIN 
                siswa 
            ON 
                kelas.id_kelas=siswa.id_kelas 
            INNER JOIN 
                komputer 
            ON 
                siswa.id_komputer=komputer.id_komputer
            WHERE 
                siswa.deleted_at = '0000-00-00'
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
                    nis=:nis,
                    id_kelas=:id_kelas,
                    nama=:nama,
                    jenis_kelamin=:jenis_kelamin,
                    ttl=:ttl,
                    alamat=:alamat,
                    no_telepon=:no_telepon,
                    email=:email,
                    username=:username,
                    password=:password,
                    id_komputer=:id_komputer,
                    created_at=:created_at";
      
        // prepare query
        $stmt = $this->conn->prepare($query);
      
        // sanitize
        $this->nis=htmlspecialchars(strip_tags($this->nis));
        $this->id_kelas=htmlspecialchars(strip_tags($this->id_kelas));
        $this->nama=htmlspecialchars(strip_tags($this->nama));
        $this->jenis_kelamin=htmlspecialchars(strip_tags($this->jenis_kelamin));
        $this->ttl=htmlspecialchars(strip_tags($this->ttl));
        $this->alamat=htmlspecialchars(strip_tags($this->alamat));
        $this->no_telepon=htmlspecialchars(strip_tags($this->nama));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->id_komputer=htmlspecialchars(strip_tags($this->id_komputer));
        $this->created_at=htmlspecialchars(strip_tags($this->created_at));
      

        // bind values
        $stmt->bindParam(":nis", $this->nis);
        $stmt->bindParam(":id_kelas", $this->id_kelas);
        $stmt->bindParam(":nama", $this->nama);
        $stmt->bindParam(":jenis_kelamin", $this->jenis_kelamin);
        $stmt->bindParam(":ttl", $this->ttl);
        $stmt->bindParam(":alamat", $this->alamat);
        $stmt->bindParam(":no_telepon", $this->no_telepon);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":username", $this->username);
        
        $stmt->bindParam(":id_komputer", $this->id_komputer);
        $stmt->bindParam(":created_at", $this->created_at);

        // $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $this->password);
      
        // execute query
        if($stmt->execute()){
            return true;
        }
      
        return false;
          
    }

    function read(){
    
        // query to read single record
        $query = "SELECT siswa.nis,kelas.id_kelas,kelas.nama_kelas,siswa.nama,siswa.jenis_kelamin,siswa.ttl,siswa.alamat,siswa.no_telepon,siswa.email,siswa.username,siswa.password,komputer.id_komputer,komputer.nama_komputer,siswa.created_at,siswa.updated_at
                FROM 
                    kelas 
                INNER JOIN 
                    siswa 
                ON 
                    kelas.id_kelas=siswa.id_kelas 
                INNER JOIN 
                    komputer 
                ON 
                    siswa.id_komputer=komputer.id_komputer
                WHERE
                    siswa.nis = ? &&
                    siswa.deleted_at = '0000-00-00'
                LIMIT
                    0,1";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // bind id of product to be updated
        $stmt->bindParam(1, $this->nis);
    
        // execute query
        $stmt->execute();
    
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // set values to object properties
        $this->nis = $row['nis'];
        $this->id_kelas = $row['id_kelas'];
        $this->nama_kelas = $row['nama_kelas'];
        $this->nama = $row['nama'];
        $this->jenis_kelamin = $row['jenis_kelamin'];
        $this->ttl = $row['ttl'];
        $this->alamat = $row['alamat'];
        $this->no_telepon = $row['no_telepon'];
        $this->email = $row['email'];
        $this->username = $row['username'];
        $this->password = $row['password'];
        $this->id_komputer = $row['id_komputer'];
        $this->nama_komputer = $row['nama_komputer'];
        $this->created_at = $row['created_at'];
        $this->updated_at = $row['updated_at'];
    }
    
    function update(){
        // update query
        $query = "UPDATE
                " . $this->table_name . "
                SET
                    nis=:nis,
                    id_kelas=:id_kelas,
                    nama=:nama,
                    jenis_kelamin=:jenis_kelamin,
                    ttl=:ttl,
                    alamat=:alamat,
                    no_telepon=:no_telepon,
                    email=:email,
                    username=:username,
                    password=:password,
                    id_komputer=:id_komputer,
                    updated_at=:updated_at
                WHERE
                    nis=:nis";
      
        // prepare query statement
        $stmt = $this->conn->prepare($query);
      
        // sanitize
        $this->nis=htmlspecialchars(strip_tags($this->nis));
        $this->id_kelas=htmlspecialchars(strip_tags($this->id_kelas));
        $this->nama=htmlspecialchars(strip_tags($this->nama));
        $this->jenis_kelamin=htmlspecialchars(strip_tags($this->jenis_kelamin));
        $this->ttl=htmlspecialchars(strip_tags($this->ttl));
        $this->alamat=htmlspecialchars(strip_tags($this->alamat));
        $this->no_telepon=htmlspecialchars(strip_tags($this->no_telepon));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->id_komputer=htmlspecialchars(strip_tags($this->id_komputer));
        $this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
      
        // bind values
        $stmt->bindParam(":nis", $this->nis);
        $stmt->bindParam(":id_kelas", $this->id_kelas);
        $stmt->bindParam(":nama", $this->nama);
        $stmt->bindParam(":jenis_kelamin", $this->jenis_kelamin);
        $stmt->bindParam(":ttl", $this->ttl);
        $stmt->bindParam(":alamat", $this->alamat);
        $stmt->bindParam(":no_telepon", $this->no_telepon);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":id_komputer", $this->id_komputer);
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
                    nis=:nis";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->nis=htmlspecialchars(strip_tags($this->nis));
        $this->deleted_at=htmlspecialchars(strip_tags($this->deleted_at));

        // bind values
        $stmt->bindParam(":nis", $this->nis);
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
        $query = "SELECT siswa.nis,kelas.id_kelas,kelas.nama_kelas,siswa.nama,siswa.jenis_kelamin,siswa.ttl,siswa.alamat,siswa.no_telepon,siswa.email,siswa.username,siswa.password,komputer.id_komputer,komputer.nama_komputer,siswa.created_at,siswa.updated_at,siswa.deleted_at
                FROM 
                    kelas 
                INNER JOIN 
                    siswa 
                ON 
                    kelas.id_kelas=siswa.id_kelas 
                INNER JOIN 
                    komputer 
                ON 
                    siswa.id_komputer=komputer.id_komputer
                WHERE
                    siswa.deleted_at != '0000-00-00'
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
                    nis=:nis";

        // prepare query statement
        foreach ($this->selected as $key => $value) {
            $stmt = $this->conn->prepare($query);    
            $this->nis=htmlspecialchars(strip_tags($value->nis));
            $this->deleted_at=htmlspecialchars(strip_tags($this->deleted_at));
            $stmt->bindParam(":nis", $value->nis);
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
                    nis=:nis";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->nis=htmlspecialchars(strip_tags($this->nis));

        // bind values
        $stmt->bindParam(":nis", $this->nis);

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
                    nis=:nis";

        // prepare query statement
        foreach ($this->selected as $key => $value) {
            $stmt = $this->conn->prepare($query);    
            $this->nis=htmlspecialchars(strip_tags($value->nis));
            $stmt->bindParam(":nis", $value->nis);
            $stmt->execute();
        }
        return true;
    }

    function forceDelete(){

        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE nis = ?";
  
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->nis));
    
        // bind id of record to delete
        $stmt->bindParam(1, $this->nis);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;

    }

    function multiForceDelete(){
        // query
        $query = "DELETE FROM " . $this->table_name . " WHERE nis = :nis";

        // prepare query statement
        print_r($this->selected);
        foreach ($this->selected as $key => $value) {
            $stmt = $this->conn->prepare($query);    
            $this->nis=htmlspecialchars(strip_tags($value->nis));
            $stmt->bindParam(":nis", $value->nis);
            $stmt->execute();
        }
        return true;
    }
}
?>