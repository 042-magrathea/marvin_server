<?php

/**
 * Created by PhpStorm.
 * User: tricoman
 * Date: 4/11/16
 * Time: 13:41
 */
class RankingQuery extends Query {

    public function getCustomEntries(array $fields, array $filterFields, array $filterArguments)
    {
        // TODO: Implement getCustomEntries() method.
    }

    public function getParseEntries() {
        $usersIdSql = "SELECT "
    }

    public function getAllEntries() {
        $sql = "SELECT * FROM TOURNAMENT_HOST;";

        $result = $this->getArraySQL($sql);

        $this->adapter->closeConnection();

        return $result;
    }

}