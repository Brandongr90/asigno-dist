<?php
require_once("../config/config.php");
require_once("../models/Tasks.php");
// header("Access-Control-Allow-Origin: *");
// header("Content-Type: application/json; charset=utf-8");
// header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
// header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');

$tasks = new Tasks();
$body = json_decode(file_get_contents("php://input"), true);

switch ($_GET["option"]) {

    case "login":
        $datos = $tasks->login($body['email'], $body['pass']);
        echo json_encode($datos);
        break;

    case "getAll":
        $datos = $tasks->getAll();
        echo json_encode($datos);
        break;

    case "getAllowed":
        $datos = $tasks->getAllowed($body['team']);
        echo json_encode($datos);
        break;

    case "getUsers":
        $datos = $tasks->getUsers($body['team']);
        echo json_encode($datos);
        break;

    case "getAllUsers":
        $datos = $tasks->getAllUsers();
        echo json_encode($datos);
        break;

    case "getOne":
        $datos = $tasks->getOne($body["id"]);
        echo json_encode($datos);
        break;

    case "addTask1":
        $datos = $tasks->addTask1($body["title"], $body["start"], $body["end"], $body["asignment"], $body["notes"]);
        echo json_encode($datos);
        break;

    case "updateTask":
        $datos = $tasks->updateTask($body["title"], $body["start"], $body["end"], $body["status"], $body["asignment"], $body["notes"], $body["id"]);
        echo json_encode($datos);
        break;

    case "updateStatus":
        $datos = $tasks->updateStatus($body['id'], $body['status']);
        echo json_encode($datos);
        break;

    case "deleteTask":
        $datos = $tasks->deleteTask($body["id"]);
        echo json_encode($datos);
        break;

    case "addUser1":
        $datos = $tasks->addUser1($body['name'], $body['email'], $body['pass'], $body['color'], $body['admin'], $body['photo']);
        echo json_encode($datos);
        break;

    case "deleteUser":
        $datos = $tasks->deleteUser($body['id']);
        echo json_encode($datos);
        break;

    case "editUser":
        $datos = $tasks->editUser($body['name'], $body['email'], $body['pass'], $body['color'], $body['admin'], $body['id'], $body['photo']);
        echo json_encode($datos);
        break;

    case "getTeams":
        $datos = $tasks->getTeams();
        echo json_encode($datos);
        break;

    case "getUnasigned":
        $datos = $tasks->getUnasigned();
        echo json_encode($datos);
        break;

    case "addTeam":
        $datos = $tasks->addTeam($body['name']);
        echo json_encode($datos);
        break;

    case "addToTeam":
        $datos = $tasks->addToTeam($body['idTeam'], $body['idUser']);
        echo json_encode($datos);
        break;

    case "deleteTeam":
        $datos = $tasks->deleteTeam($body['team']);
        echo json_encode($datos);
        break;

    case "getTeam":
        $datos = $tasks->getTeam($body['team']);
        echo json_encode($datos);
        break;
}
