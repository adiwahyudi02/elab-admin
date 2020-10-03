<?php
// required to decode jwt
// include_once '../src/config/core.php';
// include_once '../libs/php-jwt-master/src/BeforeValidException.php';
// include_once '../libs/php-jwt-master/src/ExpiredException.php';
// include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
// include_once '../libs/php-jwt-master/src/JWT.php';
// include_once '../libs/php-jwt-master/src/JWK.php';

// use \Firebase\JWT\JWT;

class Attachment_work{
    
    private $conn;
    private $table_name = "attachment_work";
  
    // object properties
    public $id_attachment_work;
    public $id_work;
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
        // $this->all();
        // $data = apache_request_headers();
        // $token = $data['Authorization'];

        // // get jwt
        // $jwt=isset($token) ? $token : "";
        
        // // if jwt is not empty
        // if($jwt){
        
        //     // if decode succeed, show user details
        //     try {
        //         // decode jwt
        //         $decoded = JWT::decode($jwt, 'example_key', array('HS256'));
        //         http_response_code(200);
        //     }
        
        //     // if decode fails, it means jwt is invalid
        //     catch (Exception $e){
            
        //         // set response code
        //         http_response_code(401);
            
        //         // tell the user access denied  & show error message
        //         echo json_encode(array(
        //             "message" => "Access denied.",
        //             "error" => $e->getMessage()
        //         ));
        //         exit;
        //     }

        // }else {
        //     http_response_code(401);

        //     // tell the user access denied  & show error message
        //     echo json_encode(array(
        //         "message" => "Access denied.",
        //         "error" => $e->getMessage()
        //     ));
        //     exit;
        // }
    }

    function all(){
  
        // select all query
        $query = "SELECT attachment_work.id_attachment_work,work.id_work,attachment_work.nama_file,attachment_work.attachment,attachment_work.created_at,attachment_work.updated_at 
            FROM 
                work 
            INNER JOIN 
                attachment_work 
            ON 
                attachment_work.id_work=work.id_work
            WHERE 
                attachment_work.deleted_at = '0000-00-00'
                ";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
      
        // execute query
        $stmt->execute();
      
        return $stmt;
    }
    function insertBlob($filePath, $mime,$id_work,$nama_file) {
        $blob = fopen($filePath, 'rb');

        $sql = "INSERT INTO attachment_work(type,attachment,id_work,nama_file) VALUES(:mime,:data,:id_work,:nama_file)";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':mime', $mime);
        $stmt->bindParam(':id_work', $id_work);
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
        $query = "SELECT attachment_work.id_attachment_work,work.id_work,attachment_work.nama_file,attachment_work.attachment,attachment_work.created_at,attachment_work.updated_at,attachment_work.deleted_at 
        FROM 
            attachment_work 
        INNER JOIN 
            work 
        ON 
            work.id_work = attachment_work.id_work 
            WHERE
                attachment_work.id_attachment_work = ? &&
                attachment_work.deleted_at = '0000-00-00'
            LIMIT
                0,1";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // bind id of product to be updated
        $stmt->bindParam(1, $this->id_attachment_work);
    
        // execute query
        $stmt->execute();
    
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // set values to object properties
        $this->id_attachment_work = $row['id_attachment_work'];
        $this->id_work = $row['id_work'];
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
                    id_work=:id_work";
      
        // prepare query statement
        $stmt = $this->conn->prepare($query);
      
        // sanitize
        $this->id_work=htmlspecialchars(strip_tags($this->id_work));
        $this->id_jadwal_mapel=htmlspecialchars(strip_tags($this->id_jadwal_mapel));
        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->due_date=htmlspecialchars(strip_tags($this->due_date));
        $this->status=htmlspecialchars(strip_tags($this->status));
        $this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
      
        // bind values
        $stmt->bindParam(":id_work", $this->id_work);
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
                    id_attachment_work=:id_attachment_work";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id_attachment_work=htmlspecialchars(strip_tags($this->id_attachment_work));
        $this->deleted_at=htmlspecialchars(strip_tags($this->deleted_at));

        // bind values
        $stmt->bindParam(":id_attachment_work", $this->id_attachment_work);
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
                    id_attachment_work=:id_attachment_work";

        // prepare query statement
        foreach ($this->selected as $key => $value) {
            $stmt = $this->conn->prepare($query);    
            $this->id_attachment_work=htmlspecialchars(strip_tags($value->id_attachment_work));
            $this->deleted_at=htmlspecialchars(strip_tags($this->deleted_at));
            $stmt->bindParam(":id_attachment_work", $value->id_attachment_work);
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
                    id_attachment_work=:id_attachment_work";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id_attachment_work=htmlspecialchars(strip_tags($this->id_attachment_work));

        // bind values
        $stmt->bindParam(":id_attachment_work", $this->id_attachment_work);

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
                    id_attachment_work=:id_attachment_work";

        // prepare query statement
        foreach ($this->selected as $key => $value) {
            $stmt = $this->conn->prepare($query);    
            $this->id_attachment_work=htmlspecialchars(strip_tags($value->id_attachment_work));
            $stmt->bindParam(":id_attachment_work", $value->id_attachment_work);
            $stmt->execute();
        }
        return true;
    }

    function forceDelete(){

        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id_attachment_work = ?";
  
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id_attachment_work));
    
        // bind id of record to delete
        $stmt->bindParam(1, $this->id_attachment_work);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;

    }

    function multiForceDelete(){
        // query
        $query = "DELETE FROM " . $this->table_name . " WHERE id_attachment_work = :id_attachment_work";

        // prepare query statement
        print_r($this->selected);
        foreach ($this->selected as $key => $value) {
            $stmt = $this->conn->prepare($query);    
            $this->id_attachment_work=htmlspecialchars(strip_tags($value->id_attachment_work));
            $stmt->bindParam(":id_attachment_work", $value->id_attachment_work);
            $stmt->execute();
        }
        return true;
    }
}
?>