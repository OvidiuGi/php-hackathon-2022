<?php
class Program{

    // database connection and table name
    private $conn;
    private $table_name = "program";

    // programme properties
    public $id_program;
    public $interval_start;
    public $interval_end;
    public $nr_max;
    public $id_room;
    public $id_type_program;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    function read_program($id){
        // select all query
        $query = "SELECT * FROM program WHERE id_program = " . $id;
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){
            // products array
            $programs_arr=array();
            $programs_arr["programmes"]=array();
            // retrieve our table contents
            // fetch() is faster than fetchAll()
            // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                // extract row
                // this will make $row['name'] to
                // just $name only
                extract($row);
                $program_item=array(
                    "id_program" => $id_program,
                    "interval_start" => $interval_start,
                    "interval_end" => $interval_end,
                    "nr_max" => $nr_max,
                    "id_room" =>$id_room,
                    "id_type_program" => $id_type_program
                );
                echo $program_item;
                array_push($programs_arr["programmes"], $program_item);
            }
          }
        return $programs_arr["programmes"];
      }
    function exists(){ //We check if exists a programme with a certain id_program
      $query = "SELECT id_program FROM program WHERE id_program = " . $this->id_program;
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $count = $stmt->rowCount();
      if($count == 0){
        return false;
      }
      else{
        return $stmt;
      }
    }
    function interval_check($user_cnp){ //returns an array with the time intervals
      $query = "SELECT id_program from registration where user_cnp =" . $user_cnp; //returns a query with the id_programs of a certain user.
      $query2 ="SELECT p.interval_start, p.interval_end from program p inner join (".  $query  . ") r WHERE p.id_program = r.id_program";//returns a query with the intervals from program table with the certain id_program.
      $stmt = $this->conn->prepare($query2);
      $stmt->execute();
      $num = $stmt->rowCount();
      if($num>0){
          // interval array
          $interval_arr=array();
          $interval_arr["intervals"]=array();
          // retrieve our table contents
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
              // extract row
              // this will make $row['name'] to
              // just $name only
              extract($row);
              $interval_item=array(
                  "interval_start" => $interval_start,
                  "interval_end" => $interval_end
              );
              array_push($interval_arr["intervals"], $interval_item);
          }
      }
      return $interval_arr["intervals"];
    }

    function check_room_interval(){//returns an array with the time intervals
      $query ="SELECT interval_start, interval_end from program WHERE id_room = " . $this->id_room;
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $num = $stmt->rowCount();
      if($num>0){
          // interval array
          $interval_arr=array();
          $interval_arr["intervals"]=array();
          // retrieve our table contents
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
              // extract row
              // this will make $row['name'] to
              // just $name only
              extract($row);
              $interval_item=array(
                  "interval_start" => $interval_start,
                  "interval_end" => $interval_end
              );
              array_push($interval_arr["intervals"], $interval_item);
          }
      }
      return $interval_arr["intervals"];
    }

    function create(){ //create a programme
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    interval_start=:interval_start, interval_end=:interval_end,
                    nr_max=:nr_max, id_room=:id_room, id_type_program=:id_type_program";
        // prepare query
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->interval_start=htmlspecialchars(strip_tags($this->interval_start));
        $this->interval_end=htmlspecialchars(strip_tags($this->interval_end));
        $this->nr_max=htmlspecialchars(strip_tags($this->nr_max));
        $this->id_room=htmlspecialchars(strip_tags($this->id_room));
        $this->id_type_program=htmlspecialchars(strip_tags($this->id_type_program));
        // bind values
        $stmt->bindParam(":interval_start", $this->interval_start);
        $stmt->bindParam(":interval_end", $this->interval_end);
        $stmt->bindParam(":nr_max", $this->nr_max);
        $stmt->bindParam(":id_room", $this->id_room);
        $stmt->bindParam(":id_type_program", $this->id_type_program);
        // execute query
        if($stmt->execute()){
            return true;
        }
        return false;
      }

      function delete(){ //delete a programme
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id_program = ?";
        // prepare query
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->id_program=htmlspecialchars(strip_tags($this->id_program));
        // bind id of record to delete
        $stmt->bindParam(1, $this->id_program);
        // execute query
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){
          return true;
        }
        return false;
      }
}
?>
