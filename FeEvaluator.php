<?php

class FeEvaluator{

    function Evaluate($state, $player_id){
        $min =  GetPeerPlayer($player_id);
        $this->CountPlayerChess($state, $player_id, $aOne, $aTwo, $aThree);
        $this->CountPlayerChess($state, $min, $bOne, $bTwo, $bThree);
        if($aThree > 0){
            return GAME_INF;
        }
        if($bThree > 0){
            return -GAME_INF;
        }
        return ($aTwo - $bTwo) * DOUBLE_WEIGHT + ($aOne - $bOne);
    }

    function CountPlayerChess($state, $player_id, &$countOne, &$countTwo, &$countThree){
        global $line_idx_tbl;
        $countOne = $countTwo = $countThree = 0;
        for($i = 0; $i < LINE_DIRECTION; $i++){
            $sameCount = 0;
            $empty = 0;
            for($j = 0; $j < LINE_CELLS; $j++){
                if($state->GetGameCell($line_idx_tbl[$i][$j]) == $player_id){
                    $sameCount++;
                }
                if($state->GetGameCell($line_idx_tbl[$i][$j]) == PLAYER_NULL){
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