<?php
class Type_Of_Program{

    // database connection and table name
    private $conn;
    private $table_name = "type_of_program";

    // type_of_program properties
    public $id_type;
    public $name;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    function exists(){ //We check if there is a type_of_program with a certain id_type
      $query = "SELECT id_type FROM type_of_program WHERE id_type = " . $this->id_type;
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $count = $stmt->rowCount();
      if($count == 0){
        return false;
      }
      else{
        return true;
      }
    }
}
?>
