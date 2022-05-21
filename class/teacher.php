<?php
// students class manages the CRUD operations
class Teacher {
    public $rfc, $clave_area, $curp, $no_tarjeta, $nombre_completo, $nombramiento, $tipo, $estado;

    public $db_table = "personal";

    // preparation of database connection
    private $conn;
    private $apiData;
    public function __construct($db) {
        $this->conn = $db;
        $this->apiData = json_decode(file_get_contents("php://input"));
    }

    // queries
    // gets all records from certain table
    public function getAllTeachers() {
        $sqlQuery = "select * from " . $this->db_table;
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();

        return $stmt;
    }

    // get single record from certain table
    public function getTeacher() {
        $sqlQuery = "select * from ".$this->db_table." where rfc = :rfc";
        $this->rfc = $this->apiData->rfc;

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":rfc", $this->rfc);
        $stmt->execute();

        return $stmt;
    }

    // create a new record in certain table
    public function addTeacher(): bool {
        $sqlQuery = "insert into ".$this->db_table." values (
                    :rfc,
                    :clave_area,
                    :curp,
                    :no_tarjeta, 
                    :nombre_completo, 
                    :nombramiento, 
                    :tipo, 
                    :estado
                )";

        $stmt = $this->conn->prepare($sqlQuery);


        // sanitize and validate
        $this->rfc = htmlspecialchars(strip_tags($this->apiData->rfc));
        $this->clave_area = htmlspecialchars(strip_tags($this->apiData->clave_area));
        $this->curp = htmlspecialchars(strip_tags($this->apiData->curp));
        $this->no_tarjeta = htmlspecialchars(strip_tags($this->apiData->no_tarjeta));
        $this->nombre_completo = htmlspecialchars(strip_tags($this->apiData->nombre_completo));
        $this->nombramiento = htmlspecialchars(strip_tags($this->apiData->nombramiento));
        $this->tipo = htmlspecialchars(strip_tags($this->apiData->tipo));
        $this->estado = htmlspecialchars(strip_tags($this->apiData->estado));

        // binding data
        $stmt->bindParam(":rfc", $this->rfc);
        $stmt->bindParam(":clave_area", $this->clave_area);
        $stmt->bindParam(":curp", $this->curp);
        $stmt->bindParam(":no_tarjeta", $this->no_tarjeta);
        $stmt->bindParam(":nombre_completo", $this->nombre_completo);
        $stmt->bindParam(":nombramiento", $this->nombramiento);
        $stmt->bindParam(":tipo", $this->tipo);
        $stmt->bindParam(":estado", $this->estado);

        // run query
        return (bool) $stmt->execute();
    }
}
