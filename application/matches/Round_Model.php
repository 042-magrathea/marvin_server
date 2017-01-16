<?php

/**
 * Created by PhpStorm.
 * User: tricoman
 * Date: 16/01/17
 * Time: 18:18
 */
class Round_Model {

    private $contestants;
    private $needBayContestant;
    private $userQueries;

    public function __construct(array $contestants){

        $this->contestants = $contestants;

        if ( count($contestants)%2 == 0 ) {
            $this->needBayContestant = false;
        } else {
            $this->needBayContestant = true;
        }
    }

    public function calculateRound(){
        //
    }
}