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

/*
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["requestName"]) && isset($_POST["fields"])
    && isset($_POST["filterFields"]) && isset($_POST["filterArguments"])) {

    $controller = new request_controller(USERS_QUERY, $_POST["requestName"], $_POST["fields"], $_POST["filterFields"], $_POST["filterArguments"]);

} else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["requestName"]) && isset($_POST["fields"])){

    $controller = new request_controller(USERS_QUERY, $_POST["requestName"], $_POST["fields"]);

} else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["requestName"])){

    if (isset($_POST["idItem"])) {

    }

    $controller = new request_controller(USERS_QUERY, $_POST["requestName"]);

}*/



if (isset($_POST["requestName"])) {

    $controller = new request_controller(USERS_QUERY, $_POST);

} else {

    $controller = new request_controller(USERS_QUERY);
}

$rawData = $controller->invoke();


echo json_encode($rawData);

/*$query = new UserQuery();

$fields = array("publicName", "name", "phone", "eMail", "ads", "privateDes", "publicDes", "userRole", "language", "datePassword", "password", "memberSince");

$values = array("tricoman", "arnau biosca", "670087387", "arnaubiosca@gmail.com", "true", "es molt bona gent", "no le dejes dinero", "editor", "catala", "2016-10-10", "cacota", "2016-10-10");




$result = $query->insertItem($fields, $values);

$rawData = array(array("0" => $result));

var_dump($rawData);

echo json_encode($rawData);*/


