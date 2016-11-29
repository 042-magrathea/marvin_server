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


if (isset($_POST["requestName"])) {

    $controller = new request_controller(USERS_QUERY, $_POST);
	$rawData = $controller->invoke();
	echo json_encode($rawData);

} else {
    $query = new UserQuery();
	$rawData = $query->getParseEntries();
	echo json_encode($rawData);    
}