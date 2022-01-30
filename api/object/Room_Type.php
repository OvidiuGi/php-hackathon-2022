<?php
class Room_Type{

    // database connection and table name
    private $conn;
    private $table_name = "room_type";

    // room_type properties
    public $id_room;
    public $id_type;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
}
?>
