<?php
include_once '../vendor/autoload.php';

// students class manages the CRUD operations
class Student {
    // TODO
    public $control, $clave_carrera, $reticula, $semestre, $estado, $plan_estudios, $nombre_completo, $nip;

    public $db_table = "alumnos";

    // preparation of database connection
    private $conn;
    private $apiData;
    public function __construct($db) {
        $this -> conn = $db;
        $this->apiData = json_decode(file_get_contents("php://input"));
    }

    // queries
    // gets all records from certain table
    public function getAllStudents() {
        $sqlQuery = "select * from " . $this -> db_table;
        $stmt = $this -> conn -> prepare($sqlQuery);
        $stmt->execute();

        return $stmt;
    }

    // get single record from certain table
    public function getStudent() {
        $sqlQuery = "select * from ".$this->db_table." where control = :control";
        $stmt = $this -> conn -> prepare($sqlQuery);
        $stmt -> bindParam(":control", $this->control);
        $stmt -> execute();

        return $stmt;
    }

    // create a new record in certain table
    public function addStudent(): bool {
        $sqlQuery = "insert into ".$this->db_table.
            " values (
                    :control, 
                    :clave_carrera, 
                    :reticula, 
                    :semestre, 
                    :estado, 
                    :plan_estudios, 
                    :nombre_completo, 
                    :nip
                )";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize and validate
        $this->control = htmlspecialchars(strip_tags($this->apiData->control));
        $this->clave_carrera = htmlspecialchars(strip_tags($this->apiData->clave_carrera));
        $this->reticula = htmlspecialchars(strip_tags($this->apiData->reticula));
        $this->semestre = htmlspecialchars(strip_tags($this->apiData->semestre));
        $this->estado = htmlspecialchars(strip_tags($this->apiData->estado));
        $this->plan_estudios = htmlspecialchars(strip_tags($this->apiData->plan_estudios));
        $this->nombre_completo = htmlspecialchars(strip_tags($this->apiData->nombre_completo));
        $this->nip = htmlspecialchars(strip_tags($this->apiData->nip));

        // binding data
        $stmt->bindParam(":control", $this->control);
        $stmt->bindParam(":clave_carrera", $this->clave_carrera);
        $stmt->bindParam(":reticula", $this->reticula);
        $stmt->bindParam(":semestre", $this->semestre);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":plan_estudios", $this->plan_estudios);
        $stmt->bindParam(":nombre_completo", $this->nombre_completo);
        $stmt->bindParam(":nip", $this->nip);

        // run query
        return (bool) $stmt->execute();
    }

    // Login function
    public function login() {
        $this->control = $this->apiData->usuario;
        $this->nip = $this->apiData->contrasena;

        // TODO JAYSON AYUDAME
        $sqlQuery = sprintf("select * from %s where control = %s", $this->db_table, $this->control);

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();

        return $stmt;
    }
}

