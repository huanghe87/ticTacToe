<?php

namespace searcher;

/**
 * 负极大值搜索类
 */
class Negamax extends Base{

    public function searchBestPlay(\game\State $state, $depth){
        $bestValue = -GAME_INF;
        $bestPos = 0;
        $maxPlayerId = $state->getCurrentPlayer();
        for($i = 0; $i < BOARD_CELLS; $i++){
            if($state->isEmptyCell($i)){
                $state->setGameCell($i, $maxPlayerId);
                $state->switchPlayer();
                $value = -$this->NegaMax($state, $depth - 1, -1, $maxPlayerId);
                if($value >= $bestValue){
                    $bestValue = $value;
                    $bestPos = $i;
                }
                $state->clearGameCell($i);
                $state->setCurrentPlayer($maxPlayerId);
            }
        }
        return $bestPos;
    }

    public function evaluateNegaMax(\game\State $state, $maxPlayerId){
        if($state->getCurrentPlayer() == $maxPlayerId){
            return $this->mEvaluator->evaluate($state, $maxPlayerId);
        }else{
            return -$this->mEvaluator->evaluate($state, $maxPlayerId);
        }
    }

    public function negaMax(\game\State $state, $depth, $color, $maxPlayerId){
        if($state->isGameOver() || ($depth == 0)){
            $val = $this->evaluateNegaMax($state, $maxPlayerId);
            return $val;
        }
        $score = -GAME_INF;
        for($i = 0; $i < BOARD_CELLS; $i++){
            if($state->isEmptyCell($i)){
                $state->setGameCell($i, $state->getCurrentPlayer());
                $state->switchPlayer();
                $value = -$this->negaMax($state, $depth - 1, -$color, $maxPlayerId);
                $state->switchPlayer();
                $state->clearGameCell($i);
                $score = max($score, $value);
            }
        }
        return $score;
    }

}