<?php

namespace Searcher;

/**
 * 负极大值的α-β剪枝搜索类
 */
class NegamaxAlphaBeta extends Base{

    public function searchBestPlay(\game\State $state, $depth){
        $bestPos = -1;
        $bestPosArr = [];
        $bestValue = -GAME_INF;
        $maxPlayerId = $state->getCurrentPlayer();
        $startPhaseCell = $this->getStartPhaseCell($state);
        if($startPhaseCell >= 0){
            return $startPhaseCell;
        }
        for($i = 0; $i < BOARD_CELLS; $i++){
            if($state->isEmptyCell($i)){
                $state->setGameCell($i, $maxPlayerId);
                $state->switchPlayer();
                $value = -$this->negaMax($state, $depth - 1, -GAME_INF, GAME_INF, $maxPlayerId);
                if($value >= $bestValue){
//                    echo 'score:'.$value.PHP_EOL;
//                    $state->printGame();
                    $bestValue = $value;
                    $bestPos = $i;
                    if($value == GAME_INF){
                        $bestPosArr[] = $i;
                    }
                }
                $state->clearGameCell($i);
                $state->switchPlayer();
            }
        }
        if($bestPosArr){
            $bestPos = $bestPosArr[array_rand($bestPosArr)];
        }
        return $bestPos;
    }

    public function getStartPhaseCell(\game\State $state){
        $bestPos = -1;
        if($state->countEmptyCell() == BOARD_CELLS){
            $pos = [0, 2, 6, 8];
            $bestPos = $pos[array_rand($pos)];
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

    public function negaMax(\game\State $state, $depth, $alpha, $beta, $maxPlayerId){
        if($state->isGameOver() || ($depth == 0)){
            $score = $this->evaluateNegaMax($state, $maxPlayerId);
            return $score;
        }
        $score = -GAME_INF;
        for($i = 0; $i < BOARD_CELLS; $i++){
            if($state->isEmptyCell($i)){
                $state->setGameCell($i, $state->getCurrentPlayer());
                $state->switchPlayer();
                $value = -$this->negaMax($state, $depth - 1, -$beta, -$alpha, $maxPlayerId);
                $state->switchPlayer();
                $state->clearGameCell($i);
                $score = max($score, $value);
                $alpha = max($alpha, $value);
                if($beta <= $alpha){
                    break;
                }
            }
        }
        return $score;
    }
}