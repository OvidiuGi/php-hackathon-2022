<?php
class Room{

    // database connection and table name
    private $conn;
    private $table_name = "room";

    // Room properties
    public $id_room;
    public $name_room;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function exists(){ //We check if there is a room with a certain id_room
      $query = "SELECT id_room FROM room WHERE id_room = " . $this->id_room;
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
