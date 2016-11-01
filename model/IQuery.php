<?php

/**
 * Created by PhpStorm.
 * User: tricoman
 * Date: 30/10/16
 * Time: 19:51
 */
interface IQuery {

    public function getCustomEntries(array $fields, array $filtersFields, array $filtersArguments);
    public function getAllEntries();

}