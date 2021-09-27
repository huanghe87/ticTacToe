<?php

namespace searcher;

class Minimax extends Base{

    public function searchBestPlay(\game\State $state, $depth){
        $bestValue = -GAME_INF;
        $bestPos = -1;
        $max_player_id = $state->getCurrentPlayer();
        for ($i = 0; $i < BOARD_CELLS; $i++){
            if($state->isEmptyCell($i)){
                $state->setGameCell($i, $max_player_id);
                $state->switchPlayer();
                $value = $this->miniMax($state, $depth - 1, $max_player_id);
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

    public function miniMax(\game\State $state, $depth, $max_player_id){
        if($state->isGameOver() || ($depth == 0)){
            return $this->mEvaluator->evaluate($state, $max_player_id);
        }
        $current_player = $state->getCurrentPlayer();
        $score = ($current_player == $max_player_id) ? -GAME_INF : GAME_INF;
        for($i = 0; $i < BOARD_CELLS; $i++){
            if ($state->isEmptyCell($i)) {
                $state->setGameCell($i, $current_player);
                $state->switchPlayer();
                $value = $this->miniMax($state, $depth - 1, $max_player_id);
                $state->switchPlayer();
                $state->clearGameCell($i);
                if($current_player == $max_player_id){
                    $score = max($score, $value);
                }else{
                    $score = min($score, $value);
                }
            }
        }
        return $score;
    }

}