<?php

namespace Searcher;

class Negamax extends Base{

    public function SearchBestPlay(\Game\State $state, $depth){
        $bestValue = -GAME_INF;
        $bestPos = 0;
        $max_player_id = $state->GetCurrentPlayer();
        for($i = 0; $i < BOARD_CELLS; $i++){
            if($state->IsEmptyCell($i)){
                $state->SetGameCell($i, $max_player_id);
                $state->SwitchPlayer();
                $value = -$this->NegaMax($state, $depth - 1, -1, $max_player_id);
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

    public function EvaluateNegaMax(\Game\State $state, $max_player_id){
        if($state->GetCurrentPlayer() == $max_player_id)
            return $this->m_evaluator->Evaluate($state, $max_player_id);
        else
            return -$this->m_evaluator->Evaluate($state, $max_player_id);
    }

    public function NegaMax(\Game\State $state, $depth, $color, $max_player_id){
        if($state->IsGameOver() || ($depth == 0)){
            $val = $this->EvaluateNegaMax($state, $max_player_id);
            return $val;
        }
        $score = -GAME_INF;
        for($i = 0; $i < BOARD_CELLS; $i++){
            if($state->IsEmptyCell($i)){
                $state->SetGameCell($i, $state->GetCurrentPlayer());
                $state->SwitchPlayer();
                $value = -$this->NegaMax($state, $depth - 1, -$color, $max_player_id);
                $score = max($score, $value);
                $state->ClearGameCell($i);
            }
        }
        return $score;
    }

}