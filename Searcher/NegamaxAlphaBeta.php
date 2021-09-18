<?php

namespace Searcher;

class NegamaxAlphaBeta extends Base{

    public function SearchBestPlay(\Game\State $state, $depth){
        $bestPos = -1;
        $bestValue = -GAME_INF;
        $max_player_id = $state->GetCurrentPlayer();
        for($i = 0; $i < BOARD_CELLS; $i++){
            if($state->IsEmptyCell($i)){
                $state->SetGameCell($i, $max_player_id);
                $state->SwitchPlayer();
                $value = -$this->NegaMax($state, $depth - 1, -GAME_INF, GAME_INF, $max_player_id);
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

    public function EvaluateNegaMax(\Game\State $state, $max_player_id)
    {
        if($state->GetCurrentPlayer() == $max_player_id)
            return $this->m_evaluator->Evaluate($state, $max_player_id);
        else
            return -$this->m_evaluator->Evaluate($state, $max_player_id);
    }

    public function NegaMax(\Game\State $state, $depth, $alpha, $beta, $max_player_id){
        if($state->IsGameOver() || ($depth == 0)){
            return $this->EvaluateNegaMax($state, $max_player_id);
        }
        $score = -GAME_INF;
        for($i = 0; $i < BOARD_CELLS; $i++){
            if($state->IsEmptyCell($i)){
                $state->SetGameCell($i, $state->GetCurrentPlayer());
                $state->SwitchPlayer();
                $value = -$this->NegaMax($state, $depth - 1, -$beta, -$alpha, $max_player_id);
                $state->SwitchPlayer();
                $state->ClearGameCell($i);
                $score = max($score, $value);
                $alpha = max($alpha, $value);
                if($beta <= $alpha)
                    break;
            }
        }
        return $score;
    }
}