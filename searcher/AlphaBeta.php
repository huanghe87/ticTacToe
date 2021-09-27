<?php

namespace searcher;

class AlphaBeta extends Base{

    public function searchBestPlay(\game\State $state, $depth){
        $bestValue = -GAME_INF;
        $bestPos = 0;
        $max_player_id = $state->getCurrentPlayer();
        for($i = 0; $i < BOARD_CELLS; $i++){
            if($state->isEmptyCell($i)){
                $state->setGameCell($i, $max_player_id);
                $state->switchPlayer();
                $value = $this->alphaBeta($state, $depth - 1, -GAME_INF, GAME_INF, $max_player_id);
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

    public function alphaBeta(\game\State $state, $depth, $alpha, $beta, $max_player_id){
        if($state->isGameOver() || ($depth == 0)){
            return $this->mEvaluator->evaluate($state, $max_player_id);
        }
        if($state->getCurrentPlayer() == $max_player_id){
            for($i = 0; $i < BOARD_CELLS; $i++){
                if($state->isEmptyCell($i)){
                    $state->setGameCell($i, $state->getCurrentPlayer());
                    $state->switchPlayer();
                    $value = $this->alphaBeta($state, $depth - 1, $alpha, $beta, $max_player_id);
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
                    $value = $this->alphaBeta($state, $depth - 1, $alpha, $beta, $max_player_id);
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