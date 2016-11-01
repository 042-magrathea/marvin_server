<?php
/**
 * Created by PhpStorm.
 * User: tricoman
 * Date: 1/11/16
 * Time: 20:17
 */
header("Content-type: text/plain; charset=utf-8");

include_once 'model/HostQuery.php';
include_once  'model/Query.php';


$query = new HostQuery();

$rawData = $query->getParseEntries();

echo json_encode($rawData);