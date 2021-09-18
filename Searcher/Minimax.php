<?php

namespace Searcher;

class Minimax extends Base{

    public function SearchBestPlay(\Game\State $state, $depth){
        $bestValue = -GAME_INF;
        $bestPos = -1;
        $max_player_id = $state->GetCurrentPlayer();
        for ($i = 0; $i < BOARD_CELLS; $i++){
            if($state->IsEmptyCell($i)){
                $state->SetGameCell($i, $max_player_id);
                $state->SwitchPlayer();
                $value = $this->MiniMax($state, $depth - 1, $max_player_id);
                if($value >= $bestValue){
                    $bestValue = $value;
                    $bestPos = $i;
                }
                $state->ClearGameCell($i);
            }
        }
        $state->SetCurrentPlayer($max_player_id);
        return $bestPos;
    }

    public function MiniMax(\Game\State $state, $depth, $max_player_id){
        if($state->IsGameOver() || ($depth == 0)){
            return $this->m_evaluator->Evaluate($state, $max_player_id);
        }
        $current_player = $state->GetCurrentPlayer();
        $score = ($current_player == $max_player_id) ? -GAME_INF : GAME_INF;
        for($i = 0; $i < BOARD_CELLS; $i++){
            if ($state->IsEmptyCell($i)) {
                $state->SetGameCell($i, $state->GetCurrentPlayer());
                $state->SwitchPlayer();
                $value = $this->MiniMax($state, $depth - 1, $max_player_id);
                $state->SwitchPlayer();
                $state->ClearGameCell($i);
                if($current_player == $max_player_id){
                    $score = max($score, $value);
                } else {
                    $score = min($score, $value);
                }
            }
        }
        return $score;
    }

}