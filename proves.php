<?php
/**
 * Created by PhpStorm.
 * User: tricoman
 * Date: 19/01/17
 * Time: 21:59
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');
header("Content-type: text/plain; charset=utf-8");

include_once 'application/services/Round_Model.php';

$contestants = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20");

$roundCalculator = new Round_Model($contestants);

var_dump($roundCalculator->calculateRound());