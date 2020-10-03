<?php
// required to decode jwt
include_once '../src/config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
include_once '../libs/php-jwt-master/src/JWK.php';

use \Firebase\JWT\JWT;

class Jadwal_lab{
    
    private $conn;
    private $table_name = "jadwal_lab";
  
    // object properties
    public $id_jadwal_lab;
    public $id_kelas;
    public $id_hari;
    public $jam_mulai;
    public $jam_selesai;
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
        $query = "SELECT jadwal_lab.id_jadwal_lab,kelas.id_kelas,kelas.nama_kelas,hari.id_hari,hari.hari,jadwal_lab.jam_mulai,jadwal_lab.jam_selesai,jadwal_lab.created_at,jadwal_lab.updated_at 
            FROM 
                kelas 
            INNER JOIN 
                jadwal_lab 
            ON 
                jadwal_lab.id_kelas=kelas.id_kelas 
            INNER JOIN 
                hari 
            ON
                jadwal_lab.id_hari=hari.id_hari 
            WHERE 
                jadwal_lab.deleted_at = '0000-00-00'
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
                    id_kelas=:id_kelas,
                    id_hari=:id_hari,
                    jam_mulai=:jam_mulai,
                    jam_selesai=:jam_selesai,
                    created_at=:created_at";
      
        // prepare query
        $stmt = $this->conn->prepare($query);
      
        // sanitize
        $this->id_kelas=htmlspecialchars(strip_tags($this->id_kelas));
        $this->id_hari=htmlspecialchars(strip_tags($this->id_hari));
        $this->jam_mulai=htmlspecialchars(strip_tags($this->jam_mulai));
        $this->jam_selesai=htmlspecialchars(strip_tags($this->jam_selesai));
        $this->created_at=htmlspecialchars(strip_tags($this->created_at));
      
        // bind values
        $stmt->bindParam(":id_kelas", $this->id_kelas);
        $stmt->bindParam(":id_hari", $this->id_hari);
        $stmt->bindParam(":jam_mulai", $this->jam_mulai);
        $stmt->bindParam(":jam_selesai", $this->jam_selesai);
        $stmt->bindParam(":created_at", $this->created_at);
      
        // execute query
        if($stmt->execute()){
            return true;
        }
      
        return false;
          
    }

    function read(){
    
        // query to read single record
        $query = "SELECT jadwal_lab.id_jadwal_lab,kelas.id_kelas,kelas.nama_kelas,hari.id_hari,hari.hari,jadwal_lab.jam_mulai,jadwal_lab.jam_selesai,jadwal_lab.created_at,jadwal_lab.updated_at 
                FROM 
                    kelas 
                INNER JOIN 
                    jadwal_lab 
                ON 
                    jadwal_lab.id_kelas=kelas.id_kelas 
                INNER JOIN 
                    hari 
                ON
                    jadwal_lab.id_hari=hari.id_hari 
                WHERE
                    jadwal_lab.id_jadwal_lab = ? &&
                    jadwal_lab.deleted_at = '0000-00-00'
                LIMIT
                    0,1";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // bind id of product to be updated
        $stmt->bindParam(1, $this->id_jadwal_lab);
    
        // execute query
        $stmt->execute();
    
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // set values to object properties
        $this->id_jadwal_lab = $row['id_jadwal_lab'];
        $this->id_kelas = $row['id_kelas'];
        $this->nama_kelas = $row['nama_kelas'];
        $this->id_hari = $row['id_hari'];
        $this->hari = $row['hari'];
        $this->jam_mulai = $row['jam_mulai'];
        $this->jam_selesai = $row['jam_selesai'];
        $this->created_at = $row['created_at'];
        $this->updated_at = $row['updated_at'];
    }
    
    function update(){


        echo json_encode([
            'id_jadwal_lab' => $this->id_jadwal_lab,
    
            // set property values
            'id_kelas' => $this->id_kelas,

            // set property values
            'id_hari' => $this->id_hari,

            // set property values
            'jam_mulai' => $this->jam_mulai,

            // set property values
            'jam_selesai' => $this->jam_selesai,
        ]);

        $query_cek = "SELECT *
                    FROM
                        jadwal_lab
                    WHERE
                        id_jadwal_lab = ?";
        $stmt_cek = $this->conn->prepare( $query_cek );
        $stmt_cek->bindParam(1, $this->id_jadwal_lab);
        $stmt_cek->execute();
        $row = $stmt_cek->fetch(PDO::FETCH_ASSOC);

        if ($row['id_kelas'] != $this->id_kelas) {
            $query_jm = "UPDATE
                    jadwal_mapel
                SET
                    id_kelas=:id_kelas,
                    updated_at=:updated_at
                WHERE
                    id_jadwal_lab=:id_jadwal_lab";
      
            // prepare query statement
            $stmt_jm = $this->conn->prepare($query_jm);
            $this->id_kelas=htmlspecialchars(strip_tags($this->id_kelas));
            $this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
            $this->id_jadwal_lab=htmlspecialchars(strip_tags($this->id_jadwal_lab));
            $stmt_jm->bindParam(":id_jadwal_lab", $this->id_jadwal_lab);
            $stmt_jm->bindParam(":id_kelas", $this->id_kelas);
            $stmt_jm->bindParam(":updated_at", $this->updated_at);
            $stmt_jm->execute();
        }

        // update query
        $query = "UPDATE
                " . $this->table_name . "
                SET
                    id_kelas=:id_kelas,
                    id_hari=:id_hari,
                    jam_mulai=:jam_mulai,
                    jam_selesai=:jam_selesai,
                    updated_at=:updated_at
                WHERE
                    id_jadwal_lab=:id_jadwal_lab";
      
        // prepare query statement
        $stmt = $this->conn->prepare($query);
      
        // sanitize
        $this->id_jadwal_lab=htmlspecialchars(strip_tags($this->id_jadwal_lab));
        $this->id_kelas=htmlspecialchars(strip_tags($this->id_kelas));
        $this->id_hari=htmlspecialchars(strip_tags($this->id_hari));
        $this->jam_mulai=htmlspecialchars(strip_tags($this->jam_mulai));
        $this->jam_selesai=htmlspecialchars(strip_tags($this->jam_selesai));
        $this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
      
        // bind values
        $stmt->bindParam(":id_jadwal_lab", $this->id_jadwal_lab);
        $stmt->bindParam(":id_kelas", $this->id_kelas);
        $stmt->bindParam(":id_hari", $this->id_hari);
        $stmt->bindParam(":jam_mulai", $this->jam_mulai);
        $stmt->bindParam(":jam_selesai", $this->jam_selesai);
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
                    id_jadwal_lab=:id_jadwal_lab";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id_jadwal_lab=htmlspecialchars(strip_tags($this->id_jadwal_lab));
        $this->deleted_at=htmlspecialchars(strip_tags($this->deleted_at));

        // bind values
        $stmt->bindParam(":id_jadwal_lab", $this->id_jadwal_lab);
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
                    id_jadwal_lab=:id_jadwal_lab";

        // prepare query statement
        foreach ($this->selected as $key => $value) {
            $stmt = $this->conn->prepare($query);    
            $this->id_jadwal_lab=htmlspecialchars(strip_tags($value->id_jadwal_lab));
            $this->deleted_at=htmlspecialchars(strip_tags($this->deleted_at));
            $stmt->bindParam(":id_jadwal_lab", $value->id_jadwal_lab);
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
                    id_jadwal_lab=:id_jadwal_lab";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id_jadwal_lab=htmlspecialchars(strip_tags($this->id_jadwal_lab));

        // bind values
        $stmt->bindParam(":id_jadwal_lab", $this->id_jadwal_lab);

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
                    id_jadwal_lab=:id_jadwal_lab";

        // prepare query statement
        foreach ($this->selected as $key => $value) {
            $stmt = $this->conn->prepare($query);    
            $this->id_jadwal_lab=htmlspecialchars(strip_tags($value->id_jadwal_lab));
            $stmt->bindParam(":id_jadwal_lab", $value->id_jadwal_lab);
            $stmt->execute();
        }
        return true;
    }

    function forceDelete(){

        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id_jadwal_lab = ?";
  
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id_jadwal_lab));
    
        // bind id of record to delete
        $stmt->bindParam(1, $this->id_jadwal_lab);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;

    }

    function multiForceDelete(){
        // query
        $query = "DELETE FROM " . $this->table_name . " WHERE id_jadwal_lab = :id_jadwal_lab";

        // prepare query statement
        print_r($this->selected);
        foreach ($this->selected as $key => $value) {
            $stmt = $this->conn->prepare($query);    
            $this->id_jadwal_lab=htmlspecialchars(strip_tags($value->id_jadwal_lab));
            $stmt->bindParam(":id_jadwal_lab", $value->id_jadwal_lab);
            $stmt->execute();
        }
        return true;
    }
}
?>