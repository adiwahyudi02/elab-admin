<?php
// required to decode jwt
include_once '../src/config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
include_once '../libs/php-jwt-master/src/JWK.php';

use \Firebase\JWT\JWT;

class Billing{
    
    private $conn;
    private $table_name = "billing";
  
    // object properties
    public $id_billing;
    public $id_komputer;
    public $nama_komputer;
    public $ip_address;
    public $nis;
    public $nama;
    public $date_time;
    public $jam_keluar;
    public $id_mapel;
    public $nama_mapel;
    public $id_guru;
    public $nama_guru;
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
        $query = "SELECT billing.id_billing,komputer.id_komputer,komputer.nama_komputer,komputer.ip_address,siswa.nis,siswa.id_kelas,kelas.nama_kelas,siswa.nama,billing.date_time,billing.jam_keluar,billing.created_at,billing.updated_at, lab.nama_lab
            FROM 
                komputer 
            INNER JOIN 
                billing 
            ON 
                billing.id_komputer=komputer.id_komputer 
            INNER JOIN 
                lab 
            ON 
                komputer.id_lab=lab.id_lab
            INNER JOIN 
                siswa 
            ON
                billing.nis=siswa.nis 
            INNER JOIN 
                kelas 
            ON
                siswa.id_kelas=kelas.id_kelas 
            WHERE 
                billing.deleted_at = '0000-00-00'
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
                    id_komputer=:id_komputer,
                    nis=:nis,
                    date_time=:date_time,
                    id_mapel=:id_mapel,
                    id_guru=:id_guru,
                    created_at=:created_at";
      
        // prepare query
        $stmt = $this->conn->prepare($query);
      
        // sanitize
        $this->id_komputer=htmlspecialchars(strip_tags($this->id_komputer));
        $this->nis=htmlspecialchars(strip_tags($this->nis));
        $this->date_time=htmlspecialchars(strip_tags($this->date_time));
        $this->id_mapel=htmlspecialchars(strip_tags($this->id_mapel));
        $this->id_guru=htmlspecialchars(strip_tags($this->id_guru));
        $this->created_at=htmlspecialchars(strip_tags($this->created_at));
      
        // bind values
        $stmt->bindParam(":id_komputer", $this->id_komputer);
        $stmt->bindParam(":nis", $this->nis);
        $stmt->bindParam(":date_time", $this->date_time);
        $stmt->bindParam(":id_mapel", $this->id_mapel);
        $stmt->bindParam(":id_guru", $this->id_guru);
        $stmt->bindParam(":created_at", $this->created_at);
      
        // execute query
        if($stmt->execute()){
            return true;
        }
      
        return false;
          
    }

    function read(){
    
        // query to read single record
        $query = "SELECT billing.id_billing,komputer.id_komputer,komputer.nama_komputer,siswa.nis,siswa.nama,billing.date_time, billing.jam_keluar,mapel.id_mapel,mapel.nama_mapel,guru.id_guru,guru.nama_guru,billing.created_at,billing.updated_at 
                FROM 
                    komputer 
                INNER JOIN 
                    billing 
                ON 
                    billing.id_komputer=komputer.id_komputer 
                INNER JOIN 
                    siswa 
                ON
                    billing.nis=siswa.nis 
                INNER JOIN
                    mapel 
                ON 
                    billing.id_mapel=mapel.id_mapel 
                INNER JOIN 
                    guru 
                ON 
                    billing.id_guru=guru.id_guru
                WHERE
                    billing.id_billing = ? &&
                    billing.deleted_at = '0000-00-00'
                LIMIT
                    0,1";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // bind id of product to be updated
        $stmt->bindParam(1, $this->id_billing);
    
        // execute query
        $stmt->execute();
    
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // set values to object properties
        $this->id_billing = $row['id_billing'];
        $this->id_komputer = $row['id_komputer'];
        $this->nis = $row['nis'];
        $this->date_time = $row['date_time'];
        $this->id_mapel = $row['id_mapel'];
        $this->id_guru = $row['id_guru'];
        $this->created_at = $row['created_at'];
        $this->updated_at = $row['updated_at'];
    }
    
    function update(){
        // update query
        $query = "UPDATE
                " . $this->table_name . "
                SET
                    id_komputer=:id_komputer,
                    nis=:nis,
                    date_time=:date_time,
                    id_mapel=:id_mapel,
                    id_billing=:id_billing,
                    updated_at=:updated_at
                WHERE
                    id_billing=:id_billing";
      
        // prepare query statement
        $stmt = $this->conn->prepare($query);
      
        // sanitize
        $this->id_billing=htmlspecialchars(strip_tags($this->id_billing));
        $this->id_komputer=htmlspecialchars(strip_tags($this->id_komputer));
        $this->nis=htmlspecialchars(strip_tags($this->nis));
        $this->date_time=htmlspecialchars(strip_tags($this->date_time));
        $this->id_mapel=htmlspecialchars(strip_tags($this->id_mapel));
        $this->id_billing=htmlspecialchars(strip_tags($this->id_billing));
        $this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
      
        // bind values
        $stmt->bindParam(":id_billing", $this->id_billing);
        $stmt->bindParam(":id_komputer", $this->id_komputer);
        $stmt->bindParam(":nis", $this->nis);
        $stmt->bindParam(":date_time", $this->date_time);
        $stmt->bindParam(":id_mapel", $this->id_mapel);
        $stmt->bindParam(":id_billing", $this->id_billing);
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
                    id_billing=:id_billing";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id_billing=htmlspecialchars(strip_tags($this->id_billing));
        $this->deleted_at=htmlspecialchars(strip_tags($this->deleted_at));

        // bind values
        $stmt->bindParam(":id_billing", $this->id_billing);
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
        $query = "SELECT billing.id_billing,komputer.id_komputer,komputer.nama_komputer,komputer.ip_address,siswa.nis,siswa.id_kelas,kelas.nama_kelas,siswa.nama,billing.date_time,billing.jam_keluar,billing.created_at,billing.updated_at,billing.deleted_at, lab.nama_lab
        FROM 
            komputer 
        INNER JOIN 
            billing 
        ON 
            billing.id_komputer=komputer.id_komputer 
        INNER JOIN 
            lab 
        ON 
            komputer.id_lab=lab.id_lab
        INNER JOIN 
            siswa 
        ON
            billing.nis=siswa.nis 
        INNER JOIN 
            kelas 
        ON
            siswa.id_kelas=kelas.id_kelas 
        WHERE 
            billing.deleted_at != '0000-00-00'
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
                    id_billing=:id_billing";

        // prepare query statement
        foreach ($this->selected as $key => $value) {
            $stmt = $this->conn->prepare($query);    
            $this->id_billing=htmlspecialchars(strip_tags($value->id_billing));
            $this->deleted_at=htmlspecialchars(strip_tags($this->deleted_at));
            $stmt->bindParam(":id_billing", $value->id_billing);
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
                    id_billing=:id_billing";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id_billing=htmlspecialchars(strip_tags($this->id_billing));

        // bind values
        $stmt->bindParam(":id_billing", $this->id_billing);

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
                    id_billing=:id_billing";

        // prepare query statement
        foreach ($this->selected as $key => $value) {
            $stmt = $this->conn->prepare($query);    
            $this->id_billing=htmlspecialchars(strip_tags($value->id_billing));
            $stmt->bindParam(":id_billing", $value->id_billing);
            $stmt->execute();
        }
        return true;
    }

    function forceDelete(){

        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id_billing = ?";
  
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id_billing));
    
        // bind id of record to delete
        $stmt->bindParam(1, $this->id_billing);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;

    }

    function multiForceDelete(){
        // query
        $query = "DELETE FROM " . $this->table_name . " WHERE id_billing = :id_billing";

        // prepare query statement
        print_r($this->selected);
        foreach ($this->selected as $key => $value) {
            $stmt = $this->conn->prepare($query);    
            $this->id_billing=htmlspecialchars(strip_tags($value->id_billing));
            $stmt->bindParam(":id_billing", $value->id_billing);
            $stmt->execute();
        }
        return true;
    }
}
?>