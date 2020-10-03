<?php
// 'user' object
class Auth{
 
    // database connection and table name
    private $conn;
    private $table_name = "guru";
 
    // object properties
    public $id;
    public $nip;
    public $nama_guru;
    public $username;
    public $email;
    public $role;
    public $password;
    public $created_at;
    public $updated_at;
    public $deleted_at;
 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

    // create new user record
    function create(){
    
        // insert query
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    nama = :nama,
                    username = :username,
                    email = :email,
                    password = :password,
                    created_at = :created_at";
    
        // prepare the query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->nama=htmlspecialchars(strip_tags($this->nama));
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->created_at=htmlspecialchars(strip_tags($this->created_at));
    
        // bind the values
        $stmt->bindParam(':nama', $this->nama);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(":created_at", $this->created_at);
    
        // hash the password before saving to database
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);
    
        // execute the query, also check if query was successful
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }
 

    // check if given email exist in the database
    function emailExists(){
    
        // query to check if email exists
        
        $query = "SELECT *
                FROM 
                    " . $this->table_name . "
                WHERE 
                    email = ? &&
                    deleted_at = '0000-00-00'
                LIMIT 0,1";
    
        // prepare the query
        
        $stmt = $this->conn->prepare( $query );
    
        // sanitize
        $this->email=htmlspecialchars(strip_tags($this->email));
    
        // bind given email value
        $stmt->bindParam(1, $this->email);
    
        // execute the query
        $stmt->execute();
    
        // get number of rows
        $num = $stmt->rowCount();
    
        // if email exists, assign values to object properties for easy access and use for php sessions
        if($num > 0){
    
            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // assign values to object properties
            $this->id = $row['id_guru'];
            $this->nip = $row['nip'];
            $this->nama_guru = $row['nama_guru'];
            $this->username = $row['username'];
            $this->email = $row['email'];
            $this->password = $row['password'];
            $this->role = 'guru';
            // return true because email exists in the database
            return true;
        }else{
            return false;
        }
    
        // return false if email does not exist in the database
        
    }
    
    // update() method will be here
}

?>