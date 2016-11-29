<?php
/**
 * Created by PhpStorm.
 * User: Arnau Biosca Nicolas
 * Date: 30/10/16
 * Time: 14:31
 */

header("Content-type: text/plain; charset=utf-8");

include_once 'model/User_Model.php';
include_once  'model/Query.php';
include_once  'controller/request_controller.php';



if (isset($_POST["requestName"])) {

    $controller = new request_controller("users", $_POST);

} else {

    $controller = new request_controller("users");
}

$rawData = $controller->invoke();


echo json_encode($rawData);



/*$model = new User_Model();*/


/*$rawData = $model->getIdValue(json_decode("[\"publicName\"]"), json_decode("[\"tricoman\"]"));

echo json_encode($rawData);*/


/*$rawData = $model->valueExists("[\"publicName\"]", "[\"tricoman\"]");

echo json_encode($rawData);*/
//var_dump($_POST);


/*$fields = "[\"publicName\",\"name\",\"phone\",\"eMail\",\"ads\",\"privateDes\",\"publicDes\",\"userRole\",\"language\",\"datePassword\",\"password\",\"memberSince\"]";
$values = "[\"dsasdsa\",\"dsadadsadsdsad\",\"dasds\",\"dsadad\",\"false\",\"dsa\",\"dsadsad\",\"editor\",\"Català\",\"2016-11-18\",\"sdsadsdsa\",\"2016-11-18\"]";

$arrayValues = json_decode($values);
$arrayFields = json_decode($fields);

echo var_dump($arrayFields);

$model = new User_Model();
$rawData = $model->buildInsert("USER", $arrayFields, $arrayValues);

echo var_dump($rawData);
echo $rawData;*/


























/*$query = new User_Model();

$fields = array("publicName", "name", "phone", "eMail", "ads", "privateDes", "publicDes", "userRole", "language", "datePassword", "password", "memberSince");

$values = array("tricoman", "arnau biosca", "670087387", "arnaubiosca@gmail.com", "true", "es molt bona gent", "no le dejes dinero", "editor", "catala", "2016-10-10", "cacota", "2016-10-10");




$result = $query->insertItem($fields, $values);

$rawData = array(array("0" => $result));

var_dump($rawData);

echo json_encode($rawData);*/


