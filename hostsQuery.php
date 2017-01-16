<?php
/**
 * Created by PhpStorm.
 * User: Arnau Biosca Nicolas
 * Date: 1/11/16
 * Time: 20:17
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');
header("Content-type: text/plain; charset=utf-8");

//include_once 'model/Host_Model.php';
//include_once 'model/Query.php';
include_once 'application/dbConnection/controller/request_controller.php';

//checks requestName
if (isset($_POST["requestName"])) {
    //if request has requestName
    $controller = new request_controller("hosts", $_POST);

} else {
    //if request has no requestName
    $controller = new request_controller("hosts");
}

$rawData = $controller->invoke();

echo json_encode($rawData);