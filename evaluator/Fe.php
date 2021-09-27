<?php

namespace evaluator;

class Fe implements Base{

    public function evaluate(\game\State $state, $playerId){
        $min =  getPeerPlayer($playerId);
        $this->countPlayerChess($state, $playerId, $aOne, $aTwo, $aThree);
        $this->countPlayerChess($state, $min, $bOne, $bTwo, $bThree);
        if($aThree > 0){
            return GAME_INF;
        }
        if($bThree > 0){
            return -GAME_INF;
        }
        return ($aTwo - $bTwo) * DOUBLE_WEIGHT + ($aOne - $bOne);
    }

    public function countPlayerChess(\game\State $state, $playerId, &$countOne, &$countTwo, &$countThree){
        global $lineIdxTbl;
        $countOne = $countTwo = $countThree = 0;
        for($i = 0; $i < LINE_DIRECTION; $i++){
            $sameCount = 0;
            $empty = 0;
            for($j = 0; $j < LINE_CELLS; $j++){
                if($state->GetGameCell($lineIdxTbl[$i][$j]) == $playerId){
                    $sameCount++;
                }
                if($state->GetGameCell($lineIdxTbl[$i][$j]) == PLAYER_NULL){
                    $empty++;
                }
            }
            if(($sameCount == 1) && ($empty == 2)){
                $countOne++;
            }
            if(($sameCount == 2) && ($empty == 1)){
                $countTwo++;
            }
            if(($sameCount == 3) && ($empty == 0)){
                $countThree++;
            }
        }
    }

}