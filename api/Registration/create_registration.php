<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';

// instantiate registration object
include_once '../object/Registration.php';
include_once '../object/Program.php';

$database = new Database();
$db = $database->getConnection();

$registration = new Registration($db);

// get posted data
$filecontents = file_get_contents("php://input");

$data  = json_decode($filecontents);

// make sure data is not empty
if(
    !empty($data->id_program) &&
    !empty($data->user_cnp)
){
    if(strlen($data->user_cnp) != 13){ // if the CNP is not VALID
      http_response_code(400);
      echo json_encode(array("message" => "CNP was not valid."));
      return;
    }
    //If the CNP is valid we try to check if the user tries to enroll into a inexistent programme
    $program = new Program($db) ;
    $program->id_program = $data->id_program; // in $program we store a new Program and keep only the id_program
    if(!$program->exists()){ //If the program does not exists
      http_response_code(400);
      echo json_encode(array("message" => "The program does not exist."));
      return;
    }
    //If the program exists, we check if the room is full
    $cnp_count = cnp_count($data->id_program,$db);
    $current_program = $program->read_program($data->id_program);
    if($cnp_count > $current_program[0]["nr_max"]){
      http_response_code(400);
      echo json_encode(array("message" => "The room is full."));
      return;
    }
    // set registration property values
    $registration->id_program = $data->id_program;
    $registration->user_cnp = $data->user_cnp;
    //Check if the user is already registered in the program
    if($registration->search()){
      http_response_code(400);
      echo json_encode(array("message" => "User already enrolled in this programme."));
      return;
    }
    //We check if we have another registration at that hour.
    $program->interval_start = $current_program[0]["interval_start"];
    $program->interval_end = $current_program[0]["interval_end"];
    $intervals = $program->interval_check($data->user_cnp);
    foreach ($intervals as $interval){
      if($interval["interval_end"] > $program->interval_start && $interval["interval_start"] < $program->interval_end){
        http_response_code(400);
        echo json_encode(array("message" => "The user can't be in two places at the same time."));
        return;
      }
    }
    // create the registration
    if($registration->create()){

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "Registration was created."));
    }

    // if unable to create the registration, tell the user
    else{

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Unable to create registration."));
    }
}

// tell the user data is incomplete
else{

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("message" => "Unable to create registration. Data is incomplete."));
}
    function cnp_count($id_program,$db){
      $query = "SELECT r.user_cnp FROM Registration r INNER JOIN Program p ON p.id_program = r.id_program WHERE r.id_program =" . $id_program;
      $stmt=$db->prepare($query);
      $stmt->execute();
      $count = $stmt->rowCount();
      return $count;

    }
?>
