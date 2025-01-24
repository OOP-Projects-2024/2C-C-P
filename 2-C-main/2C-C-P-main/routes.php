<?php

require_once "./Config/db.php";
require_once "./App/Get.php";
require_once "./App/Post.php";
require_once "./App/Patch.php";
require_once "./App/Delete.php"; 
require_once "./App/Auth.php";

$db = new Connection();
$pdo = $db->connect();

$post = new Post($pdo);
$get = new Get($pdo);
$patch = new Patch($pdo);
$auth = new Authentication($pdo);
$delete = new Delete($pdo, $auth);

if (isset($_REQUEST['request'])) {
    $request = explode("/", $_REQUEST['request']);
} else {
    echo "URL does not exist.";
    exit;
}

switch ($_SERVER['REQUEST_METHOD']) {

    case "GET":    
        switch ($request[0]) {
            case "taxi":
                if (isset($request[1])) {
                    $taxi_id = $get->getTaxiById($request[1]);
                    if ($taxi_id) {
                        echo json_encode($taxi_id);
                    } else {
                        echo json_encode(['error' => 'Taxi not found.', 'code' => 404]);
                    }
                } else { 
                    echo json_encode($get->getTaxi());
                }
                break;

            case "log":
                echo json_encode($get->getLogs($request[1] ?? date("Y-m-d")));
                break;

            case "account":
                if (isset($request[1])) {
                    $id = $get->getAccountById($request[1]);
                    if($id){
                        echo json_encode($id);
                    }else {
                        echo json_encode(['error' => 'Account not found.', 'code' => 404]);
                    }
                } else {
                    echo json_encode($get->getAllAccount());
                }
                break;

            default:
                http_response_code(401);
                echo "Invalid endpoint.";
                break;
        }
        break;

    case "POST":
        $body = json_decode(file_get_contents("php://input"));
        if (is_null($body)) {
            echo json_encode(["message" => "Invalid or empty request body.", "code" => 400]);
            break;
        }
        switch ($request[0]) {
            case "taxi":
                echo json_encode($post->postTaxi($body));
                break;

            case "account":
                echo json_encode($post->postAccount($body));
                break;

            default:
                http_response_code(401);
                echo "Invalid endpoint.";
                break;
        }
        break;

        case "DELETE":
            $body = json_decode(file_get_contents("php://input"), true);

            if (is_null($body)) {
                echo json_encode(["message" => "Invalid or empty request body.", "code" => 400]);
                break;
            }
            switch ($request[0]) {
                case "taxi":
                    if(isset($body['taxi_id'])) {
                        echo json_encode($delete->deleteTaxi($body['taxi_id']));
                    } else {
                        echo json_encode(["message" => "Taxi ID is required.", "code" => 400]);
                    }
                    break;
                case "account":
                    if(isset($body['id'])){
                        echo json_encode($delete->deleteAccount($body['id']));
                    }else {
                        echo json_encode(["message" => "Account ID is required." , "code" => 400]);
                    }
                    break;
            }
        
            case "PATCH":
                $body = json_decode(file_get_contents("php://input"));
                
                if (!isset($request[0]) || !isset($request[1])) {
                    echo json_encode([
                        "payload" => null,
                        "status" => [
                            "remark" => "failed",
                            "message" => "Invalid request format. Missing parameters.",
                        ],
                        "prepared_by" => "CyPhe",
                        "date_generated" => date("Y-m-d H:i:s")
                    ]);
                    break;
                }
            
                case "PATCH":
                    $body = json_decode(file_get_contents("php://input"));
                    
                    if (!isset($request[0]) || !isset($request[1])) {
                        echo json_encode([
                            "payload" => null,
                            "status" => [
                                "remark" => "failed",
                                "message" => "Invalid request format. Missing parameters.",
                            ],
                            "prepared_by" => "CyPhe",
                            "date_generated" => date("Y-m-d H:i:s")
                        ]);
                        break;
                    }
                
                    switch ($request[0]) {
                        case "taxi":
                            $response = $patch->patchTaxi($body, $request[1]);
                            break;
                
                        case "account":
                            $response = $patch->patchAccount($body, $request[1]);
                            break;
                
                        default:
                            $response = [
                                "payload" => null,
                                "status" => [
                                    "remark" => "failed",
                                    "message" => "Invalid request type.",
                                ],
                                "prepared_by" => "CyPhe",
                                "date_generated" => date("Y-m-d H:i:s")
                            ];
                            break;
                    }
                
                    echo json_encode([
                        "payload" => $response['data'] ?? null,
                        "status" => [
                            "remark" => $response,"code" => 200 ? "success" : "failed",
                            "message" => $response,"code" => 200 ? "Operation successful." : ($response['errmsg'] ?? "An error occurred."),
                        ],
                        "prepared_by" => "CyPhe",
                        "date_generated" => date("Y-m-d H:i:s")
                    ]);
                    break;
}
?>
