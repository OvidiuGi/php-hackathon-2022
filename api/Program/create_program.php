<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';

// instantiate program object
include_once '../object/Registration.php';
include_once '../object/Program.php';
include_once '../object/Room.php';
include_once '../object/Type_Of_Program.php';
$allowed_tokens ="abcde";

$database = new Database();
$db = $database->getConnection();

$program = new Program($db);

// get posted data
$filecontents = file_get_contents("php://input");
$header_contents = getallheaders();

$data  = json_decode($filecontents);

if($header_contents["TOKEN"] != $allowed_tokens){
    http_response_code(400);
    echo json_encode(array("message" => "The token is not valid."));
    return;
}
// make sure data is not empty
if(
    !empty($data->interval_start) &&
    !empty($data->interval_end)&&
    !empty($data->nr_max)&&
    !empty($data->id_room)&&
    !empty($data->id_type_program)

){
    //We check if the room exists
    $room = new Room($db) ;
    $room->id_room = $data->id_room;
    if(!$room->exists()){ //If the room does not exists
      http_response_code(400);
      echo json_encode(array("message" => "The room does not exist."));
      return;
    }
    //We check if the type of programme exists
    $type_of_program = new Type_Of_Program($db) ;
    $type_of_program->id_type = $data->id_type_program; //
    if(!$type_of_program->exists()){ //If the type_of_program does not exists
      http_response_code(400);
      echo json_encode(array("message" => "The type of program does not exist."));
      return;
    }
    // set product property values
    $program->interval_start = $data->interval_start;
    $program->interval_end = $data->interval_end;
    $program->nr_max = $data->nr_max;
    $program->id_room = $data->id_room;
    $program->id_type_program = $data->id_type_program;

    //We check if we have another programme at that hour.
    $intervals = $program->check_room_interval();
    foreach ($intervals as $interval){
      if($interval["interval_end"] > $program->interval_start && $interval["interval_start"] < $program->interval_end){
        http_response_code(400);
        echo json_encode(array("message" => "Programmes in the same room cannot overlap."));
        return;
      }
    }
    // create the programme
    if($program->create()){

        // set response code - 201 created
        http_response_code(201);
        echo json_encode(array("message" => "Programme was created."));
    }
    // if unable to create the programme, tell the user
    else{
        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Unable to create programme."));
    }
}

// tell the user data is incomplete
else{

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("message" => "Unable to create programee. Data is incomplete."));
}
?>
