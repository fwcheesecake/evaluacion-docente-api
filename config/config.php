<?php
// MySQL database connection class
class Database {
   private $host = "sql533.main-hosting.eu";
   private $database_name = "u326122577_evaldoc";
   private $username = "u326122577_genbumx";
   private $password = "5*cBtR\$F#gDY";

   public $secret_key = "23uidfjiq83fevaldocente2022ddafio38c";

   public $conn;

   public function getConnection() {
      $this -> conn = null;
      try {
         $this -> conn = new PDO("mysql:host=" . $this -> host . ";dbname=" . $this -> database_name, $this -> username, $this -> password);
         $this -> conn -> exec("set names utf8");
      } catch(PDOException $exception) {
         echo "Database could not be connected: " . $exception -> getMessage();
      }
      return $this -> conn;
   }
}
?>
