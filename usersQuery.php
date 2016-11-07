<?php
/**
 * Created by PhpStorm.
 * User: Arnau Biosca Nicolas
 * Date: 30/10/16
 * Time: 14:31
 */

header("Content-type: text/plain; charset=utf-8");

include_once 'model/UserQuery.php';
include_once  'model/Query.php';
include_once  'controller/request_controller.php';


if (isset($_POST["requestName"])) {

    $controller = new request_controller("users", $_POST);

} else {

    $controller = new request_controller("users");
}

$rawData = $controller->invoke();

var_dump($rawData);
echo json_encode($rawData);

























/*$query = new UserQuery();

$fields = array("publicName", "name", "phone", "eMail", "ads", "privateDes", "publicDes", "userRole", "language", "datePassword", "password", "memberSince");

$values = array("tricoman", "arnau biosca", "670087387", "arnaubiosca@gmail.com", "true", "es molt bona gent", "no le dejes dinero", "editor", "catala", "2016-10-10", "cacota", "2016-10-10");




$result = $query->insertItem($fields, $values);

$rawData = array(array("0" => $result));

var_dump($rawData);

echo json_encode($rawData);*/


