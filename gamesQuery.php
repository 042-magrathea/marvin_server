<?php
/**
 * Created by PhpStorm.
 * User: Arnau Biosca Nicolas
 * Date: 30/10/16
 * Time: 14:31
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');
header("Content-type: text/plain; charset=utf-8");

//include_once 'application/dbConnection/model/Image_Model.php';
//include_once 'application/dbConnection/model/Game_Model.php';
//include_once 'application/dbConnection/model/Query.php';
include_once 'application/dbConnection/controller/request_controller.php';



if (isset($_POST["requestName"])) {

    $controller = new request_controller("games", $_POST);

} else {

    $controller = new request_controller("games");
}

$rawData = $controller->invoke();


echo json_encode($rawData);





