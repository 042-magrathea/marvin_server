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

include_once 'model/User_Model.php';
include_once  'model/Query.php';
include_once  'controller/request_controller.php';
<<<<<<< HEAD



=======
>>>>>>> 885c18023b035df0ab7f4dc5ef791a5cbb07537f

if (isset($_POST["requestName"])) {

<<<<<<< HEAD
    $controller = new request_controller("users", $_POST);

} else {

    $controller = new request_controller("users");
}

$rawData = $controller->invoke();

=======
<<<<<<< HEAD

if (isset($_POST["requestName"])) {
>>>>>>> 885c18023b035df0ab7f4dc5ef791a5cbb07537f

    $controller = new request_controller("users", $_POST);

} else {

    $controller = new request_controller("users");
}

$rawData = $controller->invoke();

=======
if (isset($_POST["requestName"])) {

    $controller = new request_controller("users", $_POST);
>>>>>>> f7fc3bef3b6f3be22aed07ec831da1a27a6ff2f0

} else {

    $controller = new request_controller("users");
}

$rawData = $controller->invoke();

var_dump($rawData);
echo json_encode($rawData);



<<<<<<< HEAD
//$model = new User_Model();


/*
$fields = "[\"publicName\",\"name\",\"phone\",\"eMail\",\"ads\",\"privateDes\",\"publicDes\",\"language\",\"datePassword\",\"password\",\"memberSince\"]";
$filterFields = "[\"publicName\"]";
$filterArguments = "[\"tricoman\"]";

$rawData = $model->getCustomEntries(json_decode($fields), json_decode($filterFields), json_decode($filterArguments));

echo json_encode($rawData);*/

=======
<<<<<<< HEAD
/*$model = new User_Model();*/


>>>>>>> 885c18023b035df0ab7f4dc5ef791a5cbb07537f
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
<<<<<<< HEAD
$rawData = $model->buildInsertSql("USER", $arrayFields, $arrayValues);
=======
$rawData = $model->buildInsert("USER", $arrayFields, $arrayValues);
>>>>>>> 885c18023b035df0ab7f4dc5ef791a5cbb07537f

echo var_dump($rawData);
echo $rawData;*/

<<<<<<< HEAD
/*$query = new User_Model();
=======



=======
>>>>>>> f7fc3bef3b6f3be22aed07ec831da1a27a6ff2f0






















<<<<<<< HEAD
/*$query = new User_Model();
=======
/*$query = new UserQuery();
>>>>>>> f7fc3bef3b6f3be22aed07ec831da1a27a6ff2f0
>>>>>>> 885c18023b035df0ab7f4dc5ef791a5cbb07537f

$fields = array("publicName", "name", "phone", "eMail", "ads", "privateDes", "publicDes", "userRole", "language", "datePassword", "password", "memberSince");

$values = array("tricoman", "arnau biosca", "670087387", "arnaubiosca@gmail.com", "true", "es molt bona gent", "no le dejes dinero", "editor", "catala", "2016-10-10", "cacota", "2016-10-10");




$result = $query->insertItem($fields, $values);

$rawData = array(array("0" => $result));

var_dump($rawData);

echo json_encode($rawData);*/


