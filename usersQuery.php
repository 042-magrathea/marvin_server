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


$query = new UserQuery();

$rawData = $query->getParseEntries();

echo json_encode($rawData);


