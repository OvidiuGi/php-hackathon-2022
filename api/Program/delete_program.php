<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object file
include_once '../config/database.php';
include_once '../object/Program.php';
$allowed_tokens ="abcde";
// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare programme object
$program = new Program($db);

// get programme id
$data = json_decode(file_get_contents("php://input"));
$header_contents = getallheaders();
// set programme id to be deleted
$program->id_program = $data->id_program;
if($header_contents["TOKEN"] != $allowed_tokens){
    http_response_code(400);
    echo json_encode(array("message" => "The token is not valid."));
    return;
}
// delete the programme
if($program->delete()){

    // set response code - 200 ok
    http_response_code(200);

    // tell the user
    echo json_encode(array("message" => "Programme was deleted."));
}

// if unable to delete the programme
else{
    // set response code - 503 service unavailable
    http_response_code(503);

    // tell the user
    echo json_encode(array("message" => "Unable to delete programme."));
}
?>
