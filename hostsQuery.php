<?php
/**
 * Created by PhpStorm.
 * User: Arnau Biosca Nicolas
 * Date: 1/11/16
 * Time: 20:17
 */
header("Content-type: text/plain; charset=utf-8");

<<<<<<< HEAD
include_once 'model/Host_Model.php';
=======
<<<<<<< HEAD
include_once 'model/Host_Model.php';
=======
include_once 'model/HostQuery.php';
>>>>>>> f7fc3bef3b6f3be22aed07ec831da1a27a6ff2f0
>>>>>>> 885c18023b035df0ab7f4dc5ef791a5cbb07537f
include_once  'model/Query.php';
include_once  'controller/request_controller.php';


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