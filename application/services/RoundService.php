<?php

/**
 * Created by PhpStorm.
 * User: tricoman
 * Date: 16/01/17
 * Time: 18:18
 */
class RoundService {

    private $contestants;
    private $hasBayContestant;


    public function __construct(array $contestants){

        $this->contestants = $contestants;

        if ( count($contestants)%2 == 0 ) {
            $this->hasBayContestant = false;
        } else {
            array_push($this->contestants, "-1");
            $this->hasBayContestant = true;
        }
    }

    public function calculateRounds(){

        $result = array();

        $contestantsNum = count($this->contestants);
        $homeContestants = array();
        $visitorContestants = array();

        //calculate first round
        for ($i = 0; $i < ($contestantsNum/2); $i++){
            $homeContestants[$i]=$i+1;
        }
        for ($i = 0; $i < ($contestantsNum/2); $i++){
            $visitorContestants[$i]=$i+1+$contestantsNum/2;
        }

        //save first round to result array
        $roundResult = array("home" => $homeContestants, "visitor" => $visitorContestants);
        $result["round1"] = $roundResult;



        //calculate round robin
        $roundNum = 1;
        while ($roundNum < ($contestantsNum - 1)) {

            //auxiliar array create
            $auxHomeContestants = array();
            $auxVisitorContestants = array();
            //calculate first round
            for ($i = 0; $i < ($contestantsNum/2); $i++){
                $auxHomeContestants[$i]="";
            }
            for ($i = 0; $i < ($contestantsNum/2); $i++){
                $auxVisitorContestants[$i]="";
            }


            // Set original $homeContestants array first value as $auxHomeContestants array first value
            $auxHomeContestants[0]=$homeContestants[0];
            // Set original $visitorContestants array first value as $auxHomeContestants array second value
            $auxHomeContestants[1]=$visitorContestants[0];
            // Set last value of $visitorContestants as the last value of $auxVisitorContestants
            $auxVisitorContestants[count($auxVisitorContestants)-1]=$homeContestants[count($homeContestants)-1];

            // Esto es cuando hay más de cuatro equipos (con menos se hace a mano)
            if ($contestantsNum>4){
                // Todos los demás valores de p1 son los que antes estaban a su izquierda:
                for ($i=2; $i<count($homeContestants); $i++){
                    $auxHomeContestants[$i]=$homeContestants[$i-1];
                }
                // Todos los demás valores de p2 son los que antes estaban a su derecha:
                for ($i=0;$i<count($visitorContestants)-1;$i++){
                    $auxVisitorContestants[$i]=$visitorContestants[$i+1];
                }
            } else {
                $auxVisitorContestants[0]=$visitorContestants[1];
            }
            // Igualo los originales p1 y p2 a los auxiliares y los imprimo, los guardo,
            // los mando a otra clase, o lo que sea que haya que hacer con ellos
            $homeContestants=$auxHomeContestants;
            $visitorContestants=$auxVisitorContestants;
            $roundName = "round" . ($roundNum + 1);
            //save round to result array
            $roundResult = array("home" => $homeContestants, "visitor" => $visitorContestants);


            $result[$roundName] = $roundResult;

            //next round
            $roundNum++;
        }

        $result = $this->buildRoundsArray($result);

        return $result;

    }

    private function buildRoundsArray($roundSort) {

        //shuffle the array values
        shuffle($this->contestants);

        $splitContestants = array_chunk($this->contestants, count($this->contestants)/2);

        $resultArray = $roundSort;

        $roundCounter = 1;
        foreach ($roundSort as $round) {

            for ($j = 0; $j < count($round["home"]); $j++) {

                $resultArray["round".$roundCounter]["home"]["home".($j+1)] = $splitContestants[0][$j];
                unset($resultArray["round".$roundCounter]["home"][$j]);
                $resultArray["round".$roundCounter]["visitor"]["visitor".($j+1)] = $splitContestants[1][$j];
                unset($resultArray["round".$roundCounter]["visitor"][$j]);
            }
            $roundCounter++;
        }
        return $resultArray;
    }
}