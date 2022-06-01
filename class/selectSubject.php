<?php
// students class manages the CRUD operations
class SelectSubject {
    public $periodo, $no_control, $materia, $grupo, $estado_seleccion;

    public $db_table = "seleccion_materia";

    // preparation of database connection
    private $conn;
    private $apiData;
    public function __construct($db) {
        $this->conn = $db;
        $this->apiData = json_decode(file_get_contents("php://input"));
    }

    // queries
    // gets all records from certain table
    public function getAllSelectSubject() {
        $sqlQuery = "select * from " . $this->db_table;
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();

        return $stmt;
    }

    // get single record from certain table
    public function getSelectSubject() {
        $sqlQuery = "select * from ".$this->db_table." where no_control = :no_control";
        $this->no_control = $this->apiData->no_control;

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":no_control", $this->no_control);
        $stmt->execute();

        return $stmt;
    }

    // create a new record in certain table
    public function addSelectSubject(): bool {
        $sqlQuery = "insert into ".$this->db_table.
            " values (
                    :periodo,
                    :no_control,
                    :materia,
                    :grupo,
                    :estado_seleccion
                )";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize and validate
        $this->periodo = htmlspecialchars(strip_tags($this->apiData->periodo));
        $this->no_control = htmlspecialchars(strip_tags($this->apiData->no_control));
        $this->materia = htmlspecialchars(strip_tags($this->apiData->materia));
        $this->grupo = htmlspecialchars(strip_tags($this->apiData->grupo));
        $this->estado_seleccion = htmlspecialchars(strip_tags($this->apiData->estado_seleccion));

        // binding data
        $stmt->bindParam(":periodo", $this->periodo);
        $stmt->bindParam(":no_control", $this->no_control);
        $stmt->bindParam(":materia", $this->materia);
        $stmt->bindParam(":grupo", $this->grupo);
        $stmt->bindParam(":estado_seleccion", $this->estado_seleccion);

        // run query
        return (bool) $stmt->execute();
    }

    public function addSelectSubjects(): bool
    {
        $dataArr = $this->apiData;
        $arr = array();
        $sqlQuery = "";

        if(is_array($dataArr)) {
            foreach ($dataArr as $row)
                $arr[] = "('$row->periodo', '$row->no_control', '$row->materia', '$row->grupo', '$row->estado_seleccion')";

            $sqlQuery = "insert IGNORE into ".$this->db_table." values ";
            $sqlQuery .= implode(',', $arr);

            $stmt = $this->conn->prepare($sqlQuery);

            return (bool) $stmt->execute();
        }
        return false;
    }
}
