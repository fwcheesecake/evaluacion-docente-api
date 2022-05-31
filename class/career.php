<?php
// students class manages the CRUD operations
class Career {
    public $clave_carrera, $reticula, $nivel_escolar, $clave_oficial, $nombre, $nombre_abreviado, $siglas, $modalidad, $fecha_termino;

    public $db_table = "carreras";

    // preparation of database connection
    private $conn;
    private $apiData;
    public function __construct($db) {
        $this->conn = $db;
        $this->apiData = json_decode(file_get_contents("php://input"));
    }

    // queries
    // gets all records from certain table
    public function getAllCareers() {
        $sqlQuery = "select * from ".$this->db_table;
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();

        return $stmt;
    }

    // get single record from certain table
    public function getCareer() {
        $sqlQuery = "select * from ".$this->db_table." where clave_carrera = :clave_carrera";
        $this->clave_carrera = $this->apiData->clave_carrera;

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":clave_carrera", $this->clave_carrera);
        $stmt->execute();

        return $stmt;
    }

    // create a new record in certain table
    public function addCareer(): bool {
        $sqlQuery = "insert into ".$this->db_table.
            " values (
                    :clave_carrera, 
                    :reticula, 
                    :nivel_escolar,
                    :clave_oficial,
                    :nombre,
                    :nombre_abreviado,
                    :siglas,
                    :modalidad,
                    :fecha_termino
                )";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize and validate
        $this->clave_carrera= htmlspecialchars(strip_tags($this->apiData->clave_carrera));
        $this->reticula = htmlspecialchars(strip_tags($this->apiData->reticula));
        $this->nivel_escolar = htmlspecialchars(strip_tags($this->apiData->nivel_escolar));
        $this->clave_oficial = htmlspecialchars(strip_tags($this->apiData->clave_oficial));
        $this->nombre = htmlspecialchars(strip_tags($this->apiData->nombre));
        $this->nombre_abreviado = htmlspecialchars(strip_tags($this->apiData->nombre_abreviado));
        $this->siglas = htmlspecialchars(strip_tags($this->apiData->siglas));
        $this->modalidad = htmlspecialchars(strip_tags($this->apiData->modalidad));
        $this->fecha_termino = htmlspecialchars(strip_tags($this->apiData->fecha_termino));

        // binding data
        $stmt->bindParam(":clave_carrera", $this->clave_carrera);
        $stmt->bindParam(":reticula", $this->reticula);
        $stmt->bindParam(":nivel_escolar", $this->nivel_escolar);
        $stmt->bindParam(":clave_oficial", $this->clave_oficial);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":nombre_abreviado", $this->nombre_abreviado);
        $stmt->bindParam(":siglas", $this->siglas);
        $stmt->bindParam(":modalidad", $this->modalidad);
        $stmt->bindParam(":fecha_termino", $this->fecha_termino);

        // run query
        return (bool) $stmt->execute();
    }

    public function addCareers(): bool
    {
        $dataArr = $this->apiData->careerData;
        $arr = array();
        $sqlQuery = "";

        if(is_array($dataArr)) {
            foreach ($dataArr as $row)
                $arr[] = "('$row->clave_carrera', '$row->reticula', '$row->nivel_escolar', '$row->clave_oficial', '$row->nombre', '$row->nombre_abreviado', '$row->siglas', '$row->modalidad', '$row->fecha_termino')";

            $sqlQuery = "insert into ".$this->db_table." values ";
            $sqlQuery .= implode(',', $arr);

            $stmt = $this->conn->prepare($sqlQuery);

            return (bool) $stmt->execute();
        }
        return false;
    }
}
