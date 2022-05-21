<?php
// students class manages the CRUD operations
class Subject {
    public $clave_materia, $nivel_escolar, $tipo, $clave_area, $nombre, $nombre_abreviado;

    public $db_table = "materias";

    // preparation of database connection
    private $conn;
    private $apiData;
    public function __construct($db) {
        $this->conn = $db;
        $this->apiData = json_decode(file_get_contents("php://input"));
    }

    // queries
    // gets all records from certain table
    public function getAllSubjects() {
        $sqlQuery = "select * from " . $this->db_table;
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();

        return $stmt;
    }

    // get single record from certain table
    public function getSubject() {
        $sqlQuery = "select * from ".$this->db_table." where clave_materia = :clave_materia";
        $this->clave_materia = $this->apiData->clave_materia;

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":clave_materia", $this->clave_materia);
        $stmt->execute();

        return $stmt;
    }

    // create a new record in certain table
    public function addSubject(): bool {
        $sqlQuery = "insert into ".$this->db_table." values (
                    :clave_materia, 
                    :nivel_escolar, 
                    :tipo, 
                    :clave_area, 
                    :nombre, 
                    :nombre_abreviado
                )";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize and validate
        $this->clave_materia = htmlspecialchars(strip_tags($this->apiData->clave_materia));
        $this->nivel_escolar = htmlspecialchars(strip_tags($this->apiData->nivel_escolar));
        $this->tipo = htmlspecialchars(strip_tags($this->apiData->tipo));
        $this->clave_area = htmlspecialchars(strip_tags($this->apiData->clave_area));
        $this->nombre = htmlspecialchars(strip_tags($this->apiData->nombre));
        $this->nombre_abreviado = htmlspecialchars(strip_tags($this->apiData->nombre_abreviado));

        // binding data
        $stmt->bindParam(":clave_materia", $this->clave_materia);
        $stmt->bindParam(":nivel_escolar", $this->nivel_escolar);
        $stmt->bindParam(":tipo", $this->tipo);
        $stmt->bindParam(":clave_area", $this->clave_area);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":nombre_abreviado", $this->nombre_abreviado);

        // run query
        return (bool) $stmt->execute();
    }
}
