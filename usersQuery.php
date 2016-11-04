<?php
/**
 * Created by PhpStorm.
 * User: tricoman
 * Date: 30/10/16
 * Time: 14:31
 */

header("Content-type: text/plain; charset=utf-8");

include_once 'model/UserQuery.php';
include_once  'model/Query.php';
include_once  'controller/request_controller.php';


if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["fields"]) && isset($_POST["filterFields"])) {

    $controller = new request_controller(USERS_QUERY, $_POST["fields"], $_POST["filterFields"], $_POST["filterArguments"]);

} else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["fields"])){

    $controller = new request_controller(USERS_QUERY, $_POST["fields"]);

} else {
    $controller = new request_controller(USERS_QUERY);
}

$rawData = $controller->invoke();


echo json_encode($rawData);


