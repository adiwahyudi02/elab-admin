<?php
// required to decode jwt
include_once '../../src/config/core.php';
include_once '../../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../../libs/php-jwt-master/src/ExpiredException.php';
include_once '../../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../libs/php-jwt-master/src/JWT.php';
include_once '../../libs/php-jwt-master/src/JWK.php';

use \Firebase\JWT\JWT;

class Jadwal{
    
    private $conn;
    private $table_name = "jadwal_mapel";
  
    // object properties
    public $id_guru;
  
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

        $query = "SELECT jadwal_mapel.id_jadwal_mapel,kelas.id_kelas,kelas.nama_kelas,jadwal_lab.id_jadwal_lab,hari.hari,hari.id_hari,lab.id_lab,lab.nama_lab,mapel.id_mapel,mapel.nama_mapel,guru.id_guru,guru.nama_guru,jadwal_mapel.jam_mulai,jadwal_mapel.jam_selesai,jadwal_mapel.created_at,jadwal_mapel.updated_at 
            FROM 
                jadwal_mapel 
            INNER JOIN 
                kelas 
            ON 
                jadwal_mapel.id_kelas=kelas.id_kelas 
            INNER JOIN 
                jadwal_lab 
            ON
                jadwal_mapel.id_jadwal_lab=jadwal_lab.id_jadwal_lab 
            INNER JOIN 
                hari
            ON
                jadwal_lab.id_hari=hari.id_hari
            INNER JOIN 
                lab
            ON
                hari.id_lab=lab.id_lab
            INNER JOIN 
                mapel 
            ON
                jadwal_mapel.id_mapel=mapel.id_mapel
            INNER JOIN 
                guru 
            ON
                jadwal_mapel.id_guru=guru.id_guru 
            WHERE
                jadwal_mapel.id_guru = $this->id_guru &&
                jadwal_mapel.deleted_at = '0000-00-00'
                ";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
      
        // execute query
        $stmt->execute();

        $jadwal_mapels_arr=array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

            extract($row);
    
            $jadwal_mapel_item=array(
                "id_jadwal_mapel" => $id_jadwal_mapel,
                "id_lab" => $id_lab,
                "nama_lab" => $nama_lab,
                "id_hari" => $id_hari,
                "hari" => $hari,
                "id_kelas" => $id_kelas,
                "nama_kelas" => $nama_kelas,
                "id_jadwal_lab" => $id_jadwal_lab,
                "id_mapel" => $id_mapel,
                "nama_mapel" => $nama_mapel,
                "id_guru" => $id_guru,
                "nama_guru" => $nama_guru,
                "jam_mulai" => $jam_mulai,
                "jam_selesai" => $jam_selesai,
                "created_at" => $created_at,
                "updated_at" => $updated_at
            );
    
            array_push($jadwal_mapels_arr, $jadwal_mapel_item);
        }

        echo json_encode([
            "records" => $jadwal_mapels_arr
        ]);
    }

}
?>