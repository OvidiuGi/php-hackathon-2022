<?php
class Registration{

    // database connection and table name
    private $conn;
    private $table_name = "registration";

    // registration properties
    public $id_regi;
    public $id_program;
    public $user_cnp;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    // create registration
    function create(){

        // query to insert
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    id_program=:id_program, user_cnp=:user_cnp";
        // prepare query
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->id_program=htmlspecialchars(strip_tags($this->id_program));
        $this->user_cnp=htmlspecialchars(strip_tags($this->user_cnp));
        // bind values
        $stmt->bindParam(":id_program", $this->id_program);
        $stmt->bindParam(":user_cnp", $this->user_cnp);
        // execute query
        if($stmt->execute()){
            return true;
        }
        return false;
      }

      function search(){ //search for registrations based on id_program and user_cnp
        $query = "SELECT * FROM registration WHERE id_program = " . $this->id_program . " AND user_cnp = " . $this->user_cnp;
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
