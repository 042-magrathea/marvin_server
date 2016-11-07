<?php

/**
 * Created by PhpStorm.
 * User: tricoman
 * Date: 30/10/16
 * Time: 19:51
 */
interface IQuery {

    public function getCustomEntries(array $fields, array $filterFields, array $filterArguments);
    public function getAllEntries();
    public function getParseEntries();
    public function getParseEntry($itemId);
    public function getIdValue(array $filterFields, array $filterArguments);
    public function insertItem(array $fields, array $values);
}