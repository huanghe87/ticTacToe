<?php

namespace searcher;

/**
 * α-β剪枝搜索类
 */
class AlphaBeta extends Base{

    public function searchBestPlay(\game\State $state, $depth){
        $bestValue = -GAME_INF;
        $bestPos = 0;
        $maxPlayerId = $state->getCurrentPlayer();
        for($i = 0; $i < BOARD_CELLS; $i++){
            if($state->isEmptyCell($i)){
                $state->setGameCell($i, $maxPlayerId);
                $state->switchPlayer();
                $value = $this->alphaBeta($state, $depth - 1, -GAME_INF, GAME_INF, $maxPlayerId);
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

    public function alphaBeta(\game\State $state, $depth, $alpha, $beta, $maxPlayerId){
        if($state->isGameOver() || ($depth == 0)){
            return $this->mEvaluator->evaluate($state, $maxPlayerId);
        }
        if($state->getCurrentPlayer() == $maxPlayerId){
            for($i = 0; $i < BOARD_CELLS; $i++){
                if($state->isEmptyCell($i)){
                    $state->setGameCell($i, $state->getCurrentPlayer());
                    $state->switchPlayer();
                    $value = $this->alphaBeta($state, $depth - 1, $alpha, $beta, $maxPlayerId);
                    $state->switchPlayer();
                    $state->clearGameCell($i);
                    $alpha = max($alpha, $value);
                    if($beta <= $alpha){
                        break;
                    }
                }
            }
            return $alpha;
        }else{
            for($i = 0; $i < BOARD_CELLS; $i++){
                if($state->isEmptyCell($i)){
                    $state->setGameCell($i, $state->getCurrentPlayer());
                    $state->switchPlayer();
                    $value = $this->alphaBeta($state, $depth - 1, $alpha, $beta, $maxPlayerId);
                    $state->switchPlayer();
                    $state->clearGameCell($i);
                    $beta = min($beta, $value);
                    if($beta <= $alpha){
                        break;
                    }
                }
            }
            return $beta;
        }
    }
}