<?php
// students class manages the CRUD operations
class Academy {
    public $descripcion, $clave, $tipo;

    public $db_table = "academias";

    // preparation of database connection
    private $conn;
    private $apiData;
    public function __construct($db) {
        $this->conn = $db;
        $this->apiData = json_decode(file_get_contents("php://input"));
    }

    // queries
    // gets all records from certain table
    public function getAllAcademies() {
        $sqlQuery = "select * from " . $this->db_table;
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();

        return $stmt;
    }

    // get single record from certain table
    public function getAcademy() {
        $sqlQuery = "select * from ".$this->db_table." where clave = :clave";
        $this->clave = $this->apiData->clave;

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":clave", $this->clave);
        $stmt->execute();

        return $stmt;
    }

    // create a new record in certain table
    public function addAcademy(): bool {
        $sqlQuery = "insert into ".$this->db_table.
            " values (
                    :descripcion, 
                    :clave, 
                    :tipo
                )";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize and validate
        $this->descripcion= htmlspecialchars(strip_tags($this->apiData->descripcion));
        $this->clave = htmlspecialchars(strip_tags($this->apiData->clave));
        $this->tipo = htmlspecialchars(strip_tags($this->apiData->tipo));

        // binding data
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":clave", $this->clave);
        $stmt->bindParam(":tipo", $this->tipo);

        // run query
        return (bool) $stmt->execute();
    }
}
