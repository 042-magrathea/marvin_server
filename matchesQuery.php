<?php
/**
 * Created by PhpStorm.
 * User: tricoman
 * Date: 20/01/17
 * Time: 1:52
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');
header("Content-type: text/plain; charset=utf-8");


include_once 'application/dbConnection/controller/request_controller.php';


//checks requestName
if (isset($_POST["requestName"])) {
    //if request has requestName
    $controller = new request_controller(MATCHES_QUERY, $_POST);

} else {
    //if request has no requestName
    $controller = new request_controller(MATCHES_QUERY);
}

$rawData = $controller->invoke();

echo json_encode($rawData);