<?php

namespace searcher;

class Minimax extends Base{

    public function searchBestPlay(\game\State $state, $depth){
        $bestValue = -GAME_INF;
        $bestPos = -1;
        $maxPlayerId = $state->getCurrentPlayer();
        for ($i = 0; $i < BOARD_CELLS; $i++){
            if($state->isEmptyCell($i)){
                $state->setGameCell($i, $maxPlayerId);
                $state->switchPlayer();
                $value = $this->miniMax($state, $depth - 1, $maxPlayerId);
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

    public function miniMax(\game\State $state, $depth, $maxPlayerId){
        if($state->isGameOver() || ($depth == 0)){
            return $this->mEvaluator->evaluate($state, $maxPlayerId);
        }
        $current_player = $state->getCurrentPlayer();
        $score = ($current_player == $maxPlayerId) ? -GAME_INF : GAME_INF;
        for($i = 0; $i < BOARD_CELLS; $i++){
            if ($state->isEmptyCell($i)) {
                $state->setGameCell($i, $current_player);
                $state->switchPlayer();
                $value = $this->miniMax($state, $depth - 1, $maxPlayerId);
                $state->switchPlayer();
                $state->clearGameCell($i);
                if($current_player == $maxPlayerId){
                    $score = max($score, $value);
                }else{
                    $score = min($score, $value);
                }
            }
        }
        return $score;
    }

}