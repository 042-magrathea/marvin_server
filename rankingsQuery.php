<?php
/**
 * Created by PhpStorm.
 * User: Arnau Biosca Nicolas
 * Date: 4/11/16
 * Time: 19:01
 */

header("Content-type: text/plain; charset=utf-8");


include_once  'controller/request_controller.php';


//checks requestName
if (isset($_POST["requestName"])) {
    //if request has requestName
    $controller = new request_controller("rankings", $_POST);

} else {
    //if request has no requestName
    $controller = new request_controller("rankings");
}


$rawData = $controller->invoke();


echo json_encode($rawData);