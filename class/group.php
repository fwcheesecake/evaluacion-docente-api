<?php
// students class manages the CRUD operations
class Group {
    public $periodo, $materia, $grupo, $capacidad, $alumnos_inscritos, $rfc_docente;

    public $db_table = "grupos";

    // preparation of database connection
    private $conn;
    private $apiData;
    public function __construct($db) {
        $this->conn = $db;
        $this->apiData = json_decode(file_get_contents("php://input"));
    }

    // queries
    // gets all records from certain table
    public function getAllGroups() {
        $sqlQuery = "select * from " . $this->db_table;
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();

        return $stmt;
    }

    // get single record from certain table
    public function getGroup() {
        $sqlQuery = "select * from ".$this->db_table." where grupo = :grupo";
        $this->grupo = $this->apiData->grupo;

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":grupo", $this->grupo);
        $stmt->execute();

        return $stmt;
    }

    // create a new record in certain table
    public function addGroup(): bool {
        $sqlQuery = "insert into ".$this->db_table." (periodo, materia, grupo, capacidad, alumnos_inscritos, rfc_docente) values (
                    :periodo, 
                    :materia, 
                    :grupo, 
                    :capacidad, 
                    :alumnos_inscritos, 
                    :rfc_docente
                )";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize and validate
        $this->periodo = htmlspecialchars(strip_tags($this->apiData->periodo));
        $this->materia = htmlspecialchars(strip_tags($this->apiData->materia));
        $this->grupo = htmlspecialchars(strip_tags($this->apiData->grupo));
        $this->capacidad = htmlspecialchars(strip_tags($this->apiData->capacidad));
        $this->alumnos_inscritos = htmlspecialchars(strip_tags($this->apiData->alumnos_inscritos));
        $this->rfc_docente = htmlspecialchars(strip_tags($this->apiData->rfc_docente));

        // binding data
        $stmt->bindParam(":periodo", $this->periodo);
        $stmt->bindParam(":materia", $this->materia);
        $stmt->bindParam(":grupo", $this->grupo);
        $stmt->bindParam(":capacidad", $this->capacidad);
        $stmt->bindParam(":alumnos_inscritos", $this->alumnos_inscritos);
        $stmt->bindParam(":rfc_docente", $this->rfc_docente);

        // run query
        return (bool) $stmt->execute();
    }
}

