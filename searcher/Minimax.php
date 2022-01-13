<?php

namespace searcher;

/**
 * 极大极小值搜索类
 */
class Minimax extends Base
{

    public function searchBestPlay(\game\State $state, $depth)
    {
        $bestValue = -GAME_INF - 1;
        $bestPos = -1;
        $maxPlayerId = $state->getCurrentPlayer();
        for ($i = 0; $i < BOARD_CELLS; $i++) {
            if ($state->isEmptyCell($i)) {
                $state->setGameCell($i, $maxPlayerId);
                $state->switchPlayer();
                $value = $this->miniMax($state, $depth - 1, $maxPlayerId);
                if ($value > $bestValue) {
//                    echo $value.PHP_EOL;
                    $bestValue = $value;
                    $bestPos = $i;
                }
                $state->setCurrentPlayer($maxPlayerId);
                $state->clearGameCell($i);
            }
        }
        return $bestPos;
    }

    /**
     * 极大极小值搜索
     * @author huanghe
     * @since 2022-01-14
     */
    public function miniMax(\game\State $state, $depth, $maxPlayerId)
    {
        //棋局结束或搜到评估结点，返回评估值
        if ($state->isGameOver() || ($depth == 0)) {
            return $this->mEvaluator->evaluate($state, $maxPlayerId);
        }
        //获取当前棋手
        $currentPlayer = $state->getCurrentPlayer();
        //初始评估值为无穷
        $score = ($currentPlayer == $maxPlayerId) ? -GAME_INF : GAME_INF;
        for ($i = 0; $i < BOARD_CELLS; $i++) {
            //获取落子位置
            if ($state->isEmptyCell($i)) {
                //当前选手落子
                $state->setGameCell($i, $currentPlayer);
                //切换棋手为对手
                $state->switchPlayer();
                //获取子节点估值
                $value = $this->miniMax($state, $depth - 1, $maxPlayerId);
                //撤销走法
                $state->setCurrentPlayer($currentPlayer);
                $state->clearGameCell($i);
                if ($currentPlayer == $maxPlayerId) {
                    //极大值节点获取极大值
                    $score = max($score, $value);
                } else {
                    //极小值节点获取极小值
                    $score = min($score, $value);
                }
            }
        }
        //返回评估值
        return $score;
    }

}