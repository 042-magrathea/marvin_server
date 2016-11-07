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

//request names
define("ALL_VALUES", "allValues");
define("DELETE_ITEM", "deleteItem");
define("INSERT_ITEM", "insertItem");
define("MODIFY_VALUE", "modifyValue");
define("ITEM_VALUES", "itemValues");
define("SEARCH_ID", "searchIdByField");
define("CUSTOM_SEARCH", "customSearch");

class request_controller {

    public $model;
    private $post = null;
    private $requestName = null;
    private $fields = null;
    private $filterFields = null;
    private $filterArguments = null;

    public function __construct() {
        //store the parameters passed to the constructor at his call
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

    public function __construct2(String $queryMode, $post) {
        $this->setModel($queryMode);
        $this->post = $post;
    }

/*    public function __construct3(String $queryMode, $requestName, array $fields) {
        $this->setModel($queryMode);
        $this->requestName = $requestName;
        $this->fields = $fields;
    }

    public function __construct5(String $queryMode, $requestName, array $fields, array $filterFields, array $filterArguments) {
        $this->setModel($queryMode);
        $this->requestName = $requestName;
        $this->fields = $fields;
        $this->$filterFields = $filterFields;
        $this->$filterArguments = $filterArguments;
    }*/


    public function invoke() {
        /*//if $fields is the only parameter
        if ($this->fields != null && $this->filtersFields == null ) {
            $results = $this->model->getCustomEntries($this->fields, null, null);
        //if all parameters are set
        } else if ($this->fields != null && $this->filtersFields != null) {
            $results = $this->model->getCustomEntries($this->fields, $this->filtersFields, $this->filtersArguments);
        //if any parameters are set
        } else {
            $results = $this->model->getParseEntries();
        }*/
        if ($this->post == null) {
            $results = $this->model->getParseEntries();
        } else {
            $results = $this->launchRequest();
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

    private function launchRequest() {
        $this->requestName = $this->post["requestName"];
//        $this->requestName = "insertItem";
        $results = null;
        switch ($this->requestName) {
            case ALL_VALUES:
                return $this->model->getAllEntries();
                break;
            case DELETE_ITEM:
//                $results = $this->model->getAllEntries();
                break;
            case INSERT_ITEM:
                $fields = array("publicName", "name", "phone", "eMail", "ads", "privateDes", "publicDes", "userRole", "language", "datePassword", "password", "memberSince");
                $values = array("tricoman", "arnau biosca", "670087387", "arnaubiosca@gmail.com", "true", "es molt bona gent", "no le dejes dinero", "editor", "catala", "2016-10-10", "cacota", "2016-10-10");
/*                $fields = $this->post['fields'];
                $values = $this->post['values'];*/
/*                $loopSwitch = true;
                $i = 0;
                while ($loopSwitch){
                    if ($this->post['fields['.$i.']'] == null) {
                        $loopSwitch = false;
                    } else {
                        $fields = $this->post['fields['.$i.']'];
                        $values = $this->post['values['.$i.']'];
                    }
                    $i++;
                }*/

                return $this->model->insertItem($fields, $values);

                break;
            case MODIFY_VALUE:
//                $results = $this->model->getAllEntries();
                break;
            case ITEM_VALUES:
                return $this->model->getParseEntry();
                break;
            case SEARCH_ID:
                return $this->model->getCustomEntries();
                break;
            case CUSTOM_SEARCH:
                return $this->model->getCustomEntries($this->fields, $this->filtersFields, $this->filtersArguments);
                break;
            DEFAULT:
                throw new Exception("Unknow request name");

        }
    }


}