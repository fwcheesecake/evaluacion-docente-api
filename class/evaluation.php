<?php
// students class manages the CRUD operations
class Evaluation {
    public $control;

    // preparation of database connection
    private $conn;
    private $apiData;
    public function __construct($db) {
        $this->conn = $db;
        $this->apiData = json_decode(file_get_contents("php://input"));
    }

    // queries
    // get single record from certain table
    public function getTeachersAndSubjects() {
        $sqlQuery = "SELECT materia, personal.nombre_completo AS nombre_docente, rfc_docente FROM (SELECT materias.nombre as materia, rfc_docente FROM (SELECT seleccion_materia.materia, grupos.rfc_docente FROM seleccion_materia INNER JOIN grupos ON seleccion_materia.grupo = grupos.grupo WHERE seleccion_materia.no_control = :control) AS docentes INNER JOIN materias ON materias.clave_materia = docentes.materia) AS materia_docente INNER JOIN personal ON materia_docente.rfc_docente = personal.rfc;";
        $this->control = $this->apiData->control;

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":control", $this->control);
        $stmt->execute();

        return $stmt;
    }

    public function addTeachersResults(): bool
    {
        $this->control = $this->apiData->control;
        $dataArr = $this->apiData->resultados;
        $arr = array();

        if(is_array($dataArr)) {
            foreach ($dataArr as $row)
                $arr[] = "('$row->materias->rfc_docente', '$row->this->control', '$row->categorias->catA', '$row->categorias->catB', '$row->categorias->catC', '$row->categorias->catD', '$row->categorias->catE', '$row->categorias->catF', '$row->categorias->catG', '$row->categorias->catH', '$row->categorias->catI', '$row->categorias->catJ', '$row->categorias->promedio')";

            $sqlQuery = "insert into resultados_docentes values ".implode(',', $arr);

            $stmt = $this->conn->prepare($sqlQuery);

            return (bool) $stmt->execute();
        }
        return false;
    }

    public function truncateAllData(): bool
    {
        $usuario =  $this->apiData->usuario;
        $sqlPass = "SELECT contrasena from usuarios where usuario = :usuario";
        $stmt = $this->conn->prepare($sqlPass);
        $stmt->bindParam(":usuario", $usuario);
        $stmt->execute();

        if($stmt->rowCount > 0) {
            $sqlQuery = "TRUNCATE TABLE seleccion_materia;";
            $sqlQuery .= "TRUNCATE TABLE personal;";
            $sqlQuery .= "TRUNCATE TABLE alumnos;";
            $sqlQuery .= "TRUNCATE TABLE carreras;";
            $sqlQuery .= "TRUNCATE TABLE grupos;";
            $sqlQuery .= "TRUNCATE TABLE materias;";
            $sqlQuery .= "TRUNCATE TABLE periodo;";
            $sqlQuery .= "TRUNCATE TABLE academias;";

            $stmt = $this->conn->prepare($sqlQuery);

            return (bool) $stmt->execute();
        }
        return false;
    }
}
