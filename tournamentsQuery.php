<?php
/**
 * Created by PhpStorm.
 * User: Arnau Biosca Nicolas
 * Date: 1/11/16
 * Time: 20:39
 */

header("Content-type: text/plain; charset=utf-8");

<<<<<<< HEAD
include_once 'model/Tournament_Model.php';
=======
include_once 'model/TournamentQuery.php';
>>>>>>> f7fc3bef3b6f3be22aed07ec831da1a27a6ff2f0
include_once  'model/Query.php';
include_once  'controller/request_controller.php';


//checks requestName
if (isset($_POST["requestName"])) {
    //if request has requestName
    $controller = new request_controller("tournaments", $_POST);

} else {
    //if request has no requestName
    $controller = new request_controller("tournaments");
}

//get response
$rawData = $controller->invoke();


//encode and output response
echo json_encode($rawData);
