<?php
// students class manages the CRUD operations
class Period {
    public $periodo, $id_larga, $id_corta, $estado;

    public $db_table = "periodo";

    // preparation of database connection
    private $conn;
    private $apiData;
    public function __construct($db) {
        $this->conn = $db;
        $this->apiData = json_decode(file_get_contents("php://input"));
    }

    // queries
    // gets all records from certain table
    public function getAllPeriods() {
        $sqlQuery = "select * from " . $this->db_table;
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();

        return $stmt;
    }

    // get single record from certain table
    public function getPeriod() {
        $sqlQuery = "select * from ".$this->db_table." where periodo = :periodo";
        $this->periodo = $this->apiData->periodo;

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":periodo", $this->periodo);
        $stmt->execute();

        return $stmt;
    }

    // create a new record in certain table
    public function addPeriod(): bool {
        $sqlQuery = "insert into ".$this->db_table." values (
                    :periodo, 
                    :id_larga, 
                    :id_corta, 
                    :estado
                )";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize and validate
        $this->periodo = htmlspecialchars(strip_tags($this->apiData->periodo));
        $this->id_larga = htmlspecialchars(strip_tags($this->apiData->id_larga));
        $this->id_corta = htmlspecialchars(strip_tags($this->apiData->id_corta));
        $this->estado = htmlspecialchars(strip_tags($this->apiData->estado));

        // binding data
        $stmt->bindParam(":periodo", $this->periodo);
        $stmt->bindParam(":id_larga", $this->id_larga);
        $stmt->bindParam(":id_corta", $this->id_corta);
        $stmt->bindParam(":estado", $this->estado);

        // run query
        return (bool) $stmt->execute();
    }

    public function addPeriods(): bool
    {
        $dataArr = $this->apiData->periodData;
        $arr = array();
        $sqlQuery = "";

        if(is_array($dataArr)) {
            foreach ($dataArr as $row)
                $arr[] = "('$row->periodo', '$row->id_larga', '$row->id_corta', '$row->estado')";

            $sqlQuery = "insert into ".$this->db_table." values ";
            $sqlQuery .= implode(',', $arr);

            $stmt = $this->conn->prepare($sqlQuery);

            return (bool) $stmt->execute();
        }
        return false;
    }
}
