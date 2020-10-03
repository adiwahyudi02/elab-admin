<?php
// required to decode jwt
include_once '../src/config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
include_once '../libs/php-jwt-master/src/JWK.php';

use \Firebase\JWT\JWT;

class Jadwal{
    
    private $conn;
    private $table_name = "lab";
  
    // object properties
    public $id_hari;
    public $hari;
    public $nama_lab;
    public $id_lab;
    public $jadwal_hari;
    public $created_at;
    public $updated_at;
    public $deleted_at;
    public $selected; //tambahan

    public $columnId;
    public $result;
    public $data;
  
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

    function read(){

        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . "
                WHERE
                    id_lab = ? &&
                    deleted_at = '0000-00-00'
                LIMIT
                    0,1";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // bind id of product to be updated
        $stmt->bindParam(1, $this->id_lab);
    
        // execute query
        $stmt->execute();
    
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // set values to object properties
        $this->id_lab = $row['id_lab'];
        $this->nama_lab = $row['nama_lab'];

  
        // get hari lab
        $query_hari = "SELECT * FROM 
                    hari
                WHERE
                    deleted_at = '0000-00-00' &&
                    id_lab = $this->id_lab
                ";

        $stmt_hari = $this->conn->prepare($query_hari);
        $stmt_hari->execute();
        $hari_arr=array();
        while ($row_hari = $stmt_hari->fetch(PDO::FETCH_ASSOC)){
            extract($row_hari);

            //get jadwal lab
            $query_jawal_lab = "SELECT jadwal_lab.*, kelas.nama_kelas, hari.hari
            FROM 
                jadwal_lab
            INNER JOIN 
                kelas
            ON 
                kelas.id_kelas = jadwal_lab.id_kelas 
            INNER JOIN 
                hari
            ON 
                hari.id_hari = jadwal_lab.id_hari 
            WHERE
                jadwal_lab.id_hari = $id_hari &&
                jadwal_lab.deleted_at = '0000-00-00' &&
                kelas.deleted_at = '0000-00-00'
            ORDER BY
                jadwal_lab.jam_mulai
            ";
            $stmt_jl = $this->conn->prepare($query_jawal_lab);
            $stmt_jl->execute();
            $jl_arr=array();

            while ($row_jl = $stmt_jl->fetch(PDO::FETCH_ASSOC)){
                extract($row_jl);

                $query_jawal_mapel = "SELECT jadwal_mapel.*, mapel.nama_mapel, guru.nama_guru, kelas.nama_kelas
                FROM 
                    jadwal_mapel
                INNER JOIN 
                    mapel
                ON 
                    mapel.id_mapel = jadwal_mapel.id_mapel
                INNER JOIN 
                    guru
                ON 
                    guru.id_guru = jadwal_mapel.id_guru
                INNER JOIN 
                    kelas
                ON 
                    kelas.id_kelas = jadwal_mapel.id_kelas
                WHERE
                    jadwal_mapel.id_jadwal_lab = $id_jadwal_lab &&
                    jadwal_mapel.deleted_at = '0000-00-00'
                ORDER BY
                    jadwal_mapel.jam_mulai
                ";
                $stmt_jm = $this->conn->prepare($query_jawal_mapel);
                $stmt_jm->execute();
                $jm_arr=array();
                while ($row_jm = $stmt_jm->fetch(PDO::FETCH_ASSOC)){
                    extract($row_jm);

                    $view_item_jm=array(
                        "id" => $row_jm['id_jadwal_mapel'],
                        "id_kelas" => $row_jm['id_kelas'],
                        "id_jadwal_lab" => $row_jm['id_jadwal_lab'],
                        "nama_kelas" => $row_jm['nama_kelas'],
                        "id_mapel" => $row_jm['id_mapel'],
                        "nama_mapel" => $row_jm['nama_mapel'],
                        "id_guru" => $row_jm['id_guru'],
                        "nama_guru" => $row_jm['nama_guru'],
                        "jam_mulai" => $row_jm['jam_mulai'],
                        "jam_selesai" => $row_jm['jam_selesai'],
                        "created_at" => $row_jm['created_at'],
                        "updated_at" => $row_jm['updated_at']
                    );
    
                    array_push($jm_arr, $view_item_jm);

                }

                $view_item_js=array(
                    "id" => $row_jl['id_jadwal_lab'],
                    "id_kelas" => $row_jl['id_kelas'],
                    "nama_kelas" => $row_jl['nama_kelas'],
                    "hari" => $row_jl['hari'],
                    "id_hari" => $row_jl['id_hari'],
                    "jam_mulai" => $row_jl['jam_mulai'],
                    "jam_selesai" => $row_jl['jam_selesai'],
                    "created_at" => $row_jl['created_at'],
                    "updated_at" => $row_jl['updated_at'],
                    "jadwal_mapel" => $jm_arr
                );

                array_push($jl_arr, $view_item_js);
            }
    
            $view_item=array(
                "id" => $id_hari,
                "hari" => $hari,
                "id_lab" => $id_lab,
                "created_at" => $created_at,
                "updated_at" => $updated_at,
                "jadwal_lab" => $jl_arr
            );
        
            array_push($hari_arr, $view_item);
        }
        if($this->id_lab == null){
            // set response code - 404 Not found
            http_response_code(200);
          
            // tell the user view_jadwal does not exist
            echo json_encode(array([
                "status" => false,
                "message" => "view jadwal does not exist."
            ]));
        }else{
            echo json_encode([
                "id_lab" => $this->id_lab,
                "nama_lab" => $this->nama_lab,
                "jadwal_hari" => $hari_arr
            ]);
        }
        
    }

    function moveKelas(){

        // echo json_encode($this->result->payload->id);

        if ($this->result->payload->id_hari != $this->columnId) {

            $query = "UPDATE
                    jadwal_lab
                SET
                    id_hari = $this->columnId
                WHERE
                    id_jadwal_lab =:id_jadwal_lab";
      
            // prepare query statement
            $stmt = $this->conn->prepare($query);
            $id_jadwal_lab=htmlspecialchars(strip_tags($this->result->payload->id));
            $stmt->bindParam(":id_jadwal_lab", $id_jadwal_lab);
            $stmt->execute();

        //     $update = Jadwal_dokter::find($this->result['payload']['id'])->update([
        //         'id_hari' => $this->columnId
        //     ]);
        }    
    }

    function switchKelas(){

        $q_update_card_1 = "UPDATE
                                jadwal_lab
                            SET
                                id_kelas = :id_kelas,
                                updated_at = :updated_at
                            WHERE
                                id_jadwal_lab = :id_jadwal_lab";

        $stmt_1 = $this->conn->prepare($q_update_card_1);
                        
        // sanitize
        $id_jadwal_lab = htmlspecialchars(strip_tags($this->data[0]->card->id));
        $id_kelas=htmlspecialchars(strip_tags($this->data[0]->card->id_kelas));
        $this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
    
        // bind values
        $stmt_1->bindParam(":id_jadwal_lab", $id_jadwal_lab);
        $stmt_1->bindParam(":id_kelas", $id_kelas);
        $stmt_1->bindParam(":updated_at", $this->updated_at);
    
        // execute the query
        $stmt_1->execute();


        $q_update_card_2 = "UPDATE
                                jadwal_lab
                            SET
                                id_kelas = :id_kelas,
                                updated_at = :updated_at
                            WHERE
                                id_jadwal_lab = :id_jadwal_lab";

        $stmt_2 = $this->conn->prepare($q_update_card_2);
                        
        // sanitize
        $id_jadwal_lab = htmlspecialchars(strip_tags($this->data[1]->card->id));
        $id_kelas=htmlspecialchars(strip_tags($this->data[1]->card->id_kelas));
        $this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
    
        // bind values
        $stmt_2->bindParam(":id_jadwal_lab", $id_jadwal_lab);
        $stmt_2->bindParam(":id_kelas", $id_kelas);
        $stmt_2->bindParam(":updated_at", $this->updated_at);
    
        // execute the query
        $stmt_2->execute();
        if($stmt_1->execute() && $stmt_2->execute()){
            return true;
        }
    }

    function switchMapel(){
        
        $q_update_card_1 = "UPDATE
                                jadwal_mapel
                            SET
                                id_mapel = :id_mapel,
                                id_guru = :id_guru,
                                updated_at = :updated_at
                            WHERE
                                id_jadwal_mapel = :id_jadwal_mapel";

        $stmt_1 = $this->conn->prepare($q_update_card_1);
                        
        // sanitize
        $id_jadwal_mapel = htmlspecialchars(strip_tags($this->data[0]->card->id));
        $id_mapel=htmlspecialchars(strip_tags($this->data[0]->card->id_mapel));
        $id_guru=htmlspecialchars(strip_tags($this->data[0]->card->id_guru));
        $this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
    
        // bind values
        $stmt_1->bindParam(":id_jadwal_mapel", $id_jadwal_mapel);
        $stmt_1->bindParam(":id_mapel", $id_mapel);
        $stmt_1->bindParam(":id_guru", $id_guru);
        $stmt_1->bindParam(":updated_at", $this->updated_at);
    
        // execute the query
        $stmt_1->execute();


        $q_update_card_2 = "UPDATE
                                jadwal_mapel
                            SET
                                id_mapel = :id_mapel,
                                id_guru = :id_guru,
                                updated_at = :updated_at
                            WHERE
                                id_jadwal_mapel = :id_jadwal_mapel";

        $stmt_2 = $this->conn->prepare($q_update_card_2);
                        
        // sanitize
        $id_jadwal_mapel = htmlspecialchars(strip_tags($this->data[1]->card->id));
        $id_mapel=htmlspecialchars(strip_tags($this->data[1]->card->id_mapel));
        $id_guru=htmlspecialchars(strip_tags($this->data[1]->card->id_guru));
        $this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
    
        // bind values
        $stmt_2->bindParam(":id_jadwal_mapel", $id_jadwal_mapel);
        $stmt_2->bindParam(":id_mapel", $id_mapel);
        $stmt_2->bindParam(":id_guru", $id_guru);
        $stmt_2->bindParam(":updated_at", $this->updated_at);
    
        // execute the query
        $stmt_2->execute();
        if($stmt_1->execute() && $stmt_2->execute()){
            return true;
        }
    }

    // function create(){
  
    //     // query to insert record
    //     $query = "INSERT INTO
    //                 " . $this->table_name . "
    //             SET
    //                 nama_mapel=:nama_mapel,
    //                 created_at=:created_at";
      
    //     // prepare query
    //     $stmt = $this->conn->prepare($query);
      
    //     // sanitize
    //     $this->nama_mapel=htmlspecialchars(strip_tags($this->nama_mapel));
    //     $this->created_at=htmlspecialchars(strip_tags($this->created_at));
      
    //     // bind values
    //     $stmt->bindParam(":nama_mapel", $this->nama_mapel);
    //     $stmt->bindParam(":created_at", $this->created_at);
      
    //     // execute query
    //     if($stmt->execute()){
    //         return true;
    //     }
      
    //     return false;
          
    // }

    // function read(){
    
    //     // query to read single record
    //     $query = "SELECT
    //                 *
    //             FROM
    //                 " . $this->table_name . "
    //             WHERE
    //                 id_mapel = ? &&
    //                 deleted_at = '0000-00-00'
    //             LIMIT
    //                 0,1";
    
    //     // prepare query statement
    //     $stmt = $this->conn->prepare( $query );
    
    //     // bind id of product to be updated
    //     $stmt->bindParam(1, $this->id_mapel);
    
    //     // execute query
    //     $stmt->execute();
    
    //     // get retrieved row
    //     $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    //     // set values to object properties
    //     $this->id_mapel = $row['id_mapel'];
    //     $this->nama_mapel = $row['nama_mapel'];
    //     $this->created_at = $row['created_at'];
    //     $this->updated_at = $row['updated_at'];
    // }
    
    // function update(){
    //     // update query
        // $query = "UPDATE
        //             " . $this->table_name . "
        //         SET
        //             nama_mapel=:nama_mapel,
        //             updated_at=:updated_at
        //         WHERE
        //             id_mapel=:id_mapel";
      
    //     // prepare query statement
    //     $stmt = $this->conn->prepare($query);
      
    //     // sanitize
    //     $this->id_mapel=htmlspecialchars(strip_tags($this->id_mapel));
    //     $this->nama_mapel=htmlspecialchars(strip_tags($this->nama_mapel));
    //     $this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
      
    //     // bind values
    //     $stmt->bindParam(":id_mapel", $this->id_mapel);
    //     $stmt->bindParam(":nama_mapel", $this->nama_mapel);
    //     $stmt->bindParam(":updated_at", $this->updated_at);
      
    //     // execute the query
    //     if($stmt->execute()){
    //         return true;
    //     }
      
    //     return false;
    // }

    // function delete(){
  
    //     // delete query
    //     $query = "UPDATE
    //                 " . $this->table_name . "
    //             SET
    //                 deleted_at=:deleted_at
    //             WHERE
    //                 id_mapel=:id_mapel";

    //     // prepare query statement
    //     $stmt = $this->conn->prepare($query);

    //     // sanitize
    //     $this->id_mapel=htmlspecialchars(strip_tags($this->id_mapel));
    //     $this->deleted_at=htmlspecialchars(strip_tags($this->deleted_at));

    //     // bind values
    //     $stmt->bindParam(":id_mapel", $this->id_mapel);
    //     $stmt->bindParam(":deleted_at", $this->deleted_at);

    //     // execute the query
    //     if($stmt->execute()){
    //         return true;
    //     }

    //     return false;
      
    //     return false;
    // }
    // function allTrash(){
  
    //     // select all query
    //     $query = "SELECT * FROM 
    //                 " . $this->table_name . "
    //             WHERE
    //                 deleted_at != '0000-00-00'
    //             ";
      
    //     // prepare query statement
    //     $stmt = $this->conn->prepare($query);
      
    //     // execute query
    //     $stmt->execute();
      
    //     return $stmt;
    // }

    // function multiDelete(){
        
    //     // delete query
    //     $query = "UPDATE
    //                 " . $this->table_name . "
    //             SET
    //                 deleted_at=:deleted_at
    //             WHERE
    //                 id_mapel=:id_mapel";

    //     // prepare query statement
    //     foreach ($this->selected as $key => $value) {
    //         $stmt = $this->conn->prepare($query);    
    //         $this->id_mapel=htmlspecialchars(strip_tags($value->id_mapel));
    //         $this->deleted_at=htmlspecialchars(strip_tags($this->deleted_at));
    //         $stmt->bindParam(":id_mapel", $value->id_mapel);
    //         $stmt->bindParam(":deleted_at", $this->deleted_at);
    //         $stmt->execute();
    //     }
    //     return true;
    // }

    // function restore(){
  
    //     // restore query
    //     $query = "UPDATE
    //                 " . $this->table_name . "
    //             SET
    //                 deleted_at = '0000-00-00'
    //             WHERE
    //                 id_mapel=:id_mapel";

    //     // prepare query statement
    //     $stmt = $this->conn->prepare($query);

    //     // sanitize
    //     $this->id_mapel=htmlspecialchars(strip_tags($this->id_mapel));

    //     // bind values
    //     $stmt->bindParam(":id_mapel", $this->id_mapel);

    //     // execute the query
    //     if($stmt->execute()){
    //         return true;
    //     }

    //     return false;
    // }


    // function multiRestore(){
    //     // restore query
    //     $query = "UPDATE
    //                 " . $this->table_name . "
    //             SET
    //                 deleted_at = '0000-00-00'
    //             WHERE
    //                 id_mapel=:id_mapel";

    //     // prepare query statement
    //     foreach ($this->selected as $key => $value) {
    //         $stmt = $this->conn->prepare($query);    
    //         $this->id_mapel=htmlspecialchars(strip_tags($value->id_mapel));
    //         $stmt->bindParam(":id_mapel", $value->id_mapel);
    //         $stmt->execute();
    //     }
    //     return true;
    // }

    // function forceDelete(){

    //     // delete query
    //     $query = "DELETE FROM " . $this->table_name . " WHERE id_mapel = ?";
  
    //     // prepare query
    //     $stmt = $this->conn->prepare($query);
    
    //     // sanitize
    //     $this->id=htmlspecialchars(strip_tags($this->id_mapel));
    
    //     // bind id of record to delete
    //     $stmt->bindParam(1, $this->id_mapel);
    
    //     // execute query
    //     if($stmt->execute()){
    //         return true;
    //     }
    
    //     return false;

    // }

    // function multiForceDelete(){
    //     // query
    //     $query = "DELETE FROM " . $this->table_name . " WHERE id_mapel = :id_mapel";

    //     // prepare query statement
    //     print_r($this->selected);
    //     foreach ($this->selected as $key => $value) {
    //         $stmt = $this->conn->prepare($query);    
    //         $this->id_mapel=htmlspecialchars(strip_tags($value->id_mapel));
    //         $stmt->bindParam(":id_mapel", $value->id_mapel);
    //         $stmt->execute();
    //     }
    //     return true;
    // }
}
?>