<?php

/**
 * Created by PhpStorm.
 * User: tricoman
 * Date: 29/10/16
 * Time: 16:55
 */

include_once 'model/UserQuery.php';
include_once 'model/TournamentQuery.php';
include_once 'model/PrizeQuery.php';
include_once 'model/HostQuery.php';
include_once 'model/RankingQuery.php';
include_once  'model/Query.php';


define("USERS_QUERY", "users");
define("PRIZES_QUERY", "prizes");
define("TOURNAMENTS_QUERY", "tournaments");
define("HOSTS_QUERY", "hosts");
define("RANKINGS_QUERY", "rankings");

class request_controller {

    public $model;
    private $fields = null;
    private $filterFields = null;
    private $filterArguments = null;

    public function __construct() {
        //store the parameters
        $params = func_get_args();
        //check the parameters number to decide wich constructor to use
        $paramsNum = func_num_args();
        //build the constructor name to use
        $constructor = '__construct'.$paramsNum;
        //check if constructor exists
        if(method_exists($this, $constructor)) {
            //call the constructor
            call_user_func_array(array($this, $constructor), $params);
        }

    }

    public function __construct1(String $queryMode) {
        $this->setModel($queryMode);
    }

    public function __construct2(String $queryMode, array $fields) {
        $this->setModel($queryMode);
        $this->fields = $fields;
    }

    public function __construct4(String $queryMode, array $fields, array $filterFields, array $filterArguments) {
        $this->setModel($queryMode);
        $this->fields = $fields;
        $this->filtersFields = $filterFields;
        $this->filtersArguments = $filterArguments;
    }


    public function invoke() {
        //if $fields is the only parameter
        if ($this->fields != null && $this->filtersFields == null ) {
            $results = $this->model->getCustomEntries($this->fields, null, null);
        //if all parameters are set
        } else if ($this->fields != null && $this->filtersFields != null) {
            $results = $this->model->getCustomEntries($this->fields, $this->filtersFields, $this->filtersArguments);
        //if any parameters are set
        } else {
            $results = $this->model->getParseEntries();
        }


        return $results;
    }

    private function setModel(String $queryMode) {
        switch ($queryMode) {
            case USERS_QUERY:
                $this->model = new UserQuery();
                break;
            case PRIZES_QUERY:
                $this->model = new PrizeQuery();
                break;
            case TOURNAMENTS_QUERY:
                $this->model = new TournamentQuery();
                break;
            case HOSTS_QUERY:
                $this->model = new HostQuery();
                break;
            case RANKINGS_QUERY:
                $this->model = new RankingQuery();
                break;
        }
    }

}