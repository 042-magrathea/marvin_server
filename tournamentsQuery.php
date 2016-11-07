<?php
/**
 * Created by PhpStorm.
 * User: tricoman
 * Date: 1/11/16
 * Time: 20:39
 */

header("Content-type: text/plain; charset=utf-8");

include_once 'model/TournamentQuery.php';
include_once  'model/Query.php';
include_once  'controller/request_controller.php';



if (isset($_POST["requestName"])) {

    $controller = new request_controller(TOURNAMENTS_QUERY, $_POST);

} else {

    $controller = new request_controller(TOURNAMENTS_QUERY);
}


$rawData = $controller->invoke();



echo json_encode($rawData);
