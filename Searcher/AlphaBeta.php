<?php

namespace Searcher;

class AlphaBeta extends Base{

    public function SearchBestPlay(\Game\State $state, $depth){
        $bestValue = -GAME_INF;
        $bestPos = 0;
        $max_player_id = $state->GetCurrentPlayer();
        for($i = 0; $i < BOARD_CELLS; $i++){
            if($state->IsEmptyCell($i)){
                $state->SetGameCell($i, $max_player_id);
                $state->SwitchPlayer();
                $value = $this->AlphaBeta($state, $depth - 1, -GAME_INF, GAME_INF, $max_player_id);
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

    public function AlphaBeta(\Game\State $state, $depth, $alpha, $beta, $max_player_id){
        if($state->IsGameOver() || ($depth == 0)){
            return $this->m_evaluator->Evaluate($state, $max_player_id);
        }
        if($state->GetCurrentPlayer() == $max_player_id){
            for($i = 0; $i < BOARD_CELLS; $i++){
                if($state->IsEmptyCell($i)){
                    $state->SetGameCell($i, $state->GetCurrentPlayer());
                    $state->SwitchPlayer();
                    $value = $this->AlphaBeta($state, $depth - 1, $alpha, $beta, $max_player_id);
                    $alpha = max($alpha, $value);
                    $state->SwitchPlayer();
                    $state->ClearGameCell($i);
                    if($beta <= $alpha)
                        break;
                }
            }
            return $alpha;
        }else{
            for($i = 0; $i < BOARD_CELLS; $i++){
                if($state->IsEmptyCell($i)){
                    $state->SetGameCell($i, $state->GetCurrentPlayer());
                    $state->SwitchPlayer();
                    $value = $this->AlphaBeta($state, $depth - 1, $alpha, $beta, $max_player_id);
                    $state->SwitchPlayer();
                    $state->ClearGameCell($i);
                    $beta = min($beta, $value);
                    if($beta <= $alpha)
                        break;
                }
            }
            return $beta;
        }
    }
}