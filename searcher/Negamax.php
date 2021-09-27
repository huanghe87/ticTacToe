<?php

namespace searcher;

class Negamax extends Base{

    public function searchBestPlay(\game\State $state, $depth){
        $bestValue = -GAME_INF;
        $bestPos = 0;
        $max_player_id = $state->getCurrentPlayer();
        for($i = 0; $i < BOARD_CELLS; $i++){
            if($state->isEmptyCell($i)){
                $state->setGameCell($i, $max_player_id);
                $state->switchPlayer();
                $value = -$this->NegaMax($state, $depth - 1, -1, $max_player_id);
                if($value >= $bestValue){
                    $bestValue = $value;
                    $bestPos = $i;
                }
                $state->clearGameCell($i);
                $state->setCurrentPlayer($max_player_id);
            }
        }
        return $bestPos;
    }

    public function evaluateNegaMax(\game\State $state, $max_player_id){
        if($state->getCurrentPlayer() == $max_player_id){
            return $this->mEvaluator->evaluate($state, $max_player_id);
        }else{
            return -$this->mEvaluator->evaluate($state, $max_player_id);
        }
    }

    public function negaMax(\game\State $state, $depth, $color, $max_player_id){
        if($state->isGameOver() || ($depth == 0)){
            $val = $this->evaluateNegaMax($state, $max_player_id);
            return $val;
        }
        $score = -GAME_INF;
        for($i = 0; $i < BOARD_CELLS; $i++){
            if($state->isEmptyCell($i)){
                $state->setGameCell($i, $state->getCurrentPlayer());
                $state->switchPlayer();
                $value = -$this->NegaMax($state, $depth - 1, -$color, $max_player_id);
                $state->switchPlayer();
                $state->clearGameCell($i);
                $score = max($score, $value);
            }
        }
        return $score;
    }

}