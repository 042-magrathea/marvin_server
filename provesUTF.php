<?php
/**
 * Created by PhpStorm.
 * User: tricoman
 * Date: 15/12/16
 * Time: 17:43
 */

$title = "nicolàs";

echo $title;

echo mb_detect_encoding($title);


$title = mb_convert_encoding("nicolàs", "UTF-8", "iso-8859-1");

echo $title;

$title = mb_detect_encoding($title);

echo $title;