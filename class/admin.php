<?php
// students class manages the CRUD operations
class Admin {
    // TODO
    public $usuario, $contrasena;

    public $db_table = "usuarios";

    // preparation of database connection
    private $conn;
    private $apiData;

    public function __construct($db) {
        $this->conn = $db;
        $this->apiData = json_decode(file_get_contents("php://input"));
    }

    // queries
    // gets all records from certain table
    public function getAllAdmins() {
        $sqlQuery = "select * from " . $this->db_table;
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();

        return $stmt;
    }

    // get single record from certain table
    public function getAdmin() {
        $sqlQuery = "select * from ".$this->db_table." where usuario = :usuario";
        $this->usuario = $this->apiData->usuario;

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":usuario", $this->usuario);
        $stmt->execute();

        return $stmt;
    }

    // create a new record in certain table
    public function addAdmin(): bool {
        $sqlQuery = "insert into ".$this->db_table.
            " values (
                    :usuario, 
                    :contrasena
                )";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize and validate
        $this->usuario = htmlspecialchars(strip_tags($this->apiData->usuario));
        $this->contrasena = htmlspecialchars(strip_tags($this->apiData->contrasena));

        // binding data
        $stmt->bindParam(":usuario", $this->usuario);
        $stmt->bindParam(":contrasena", $this->contrasena);

        // run query
        return (bool) $stmt->execute();
    }

    // Login function
    public function login() {
        $this->usuario = $this->apiData->usuario;
        $this->contrasena = $this->apiData->contrasena;

        $sqlQuery = "select * from ".$this->db_table." where usuario = :usuario";
        $this->usuario = $this->apiData->usuario;

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(":usuario", $this->usuario);

        $stmt->execute();

        return $stmt;
    }
}

