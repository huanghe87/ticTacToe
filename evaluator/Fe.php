<?php

namespace evaluator;

/**
 * 棋局评估函数
 */
class Fe implements Base
{

    /**
     * 评估当前棋局得分
     * @param object $state <p>
     * 游戏状态
     * </p>
     * @param int $playerId <p>
     * 选手id
     * </p>
     * @return int 当前棋局得分
     */
    public function evaluate(\game\State $state, $playerId)
    {
        $min = getPeerPlayer($playerId);
        list($aOne, $aTwo, $aThree) = $this->countPlayerChess($state, $playerId);
        list($bOne, $bTwo, $bThree) = $this->countPlayerChess($state, $min);
        if ($aThree > 0) {
            return GAME_INF;
        }
        if ($bThree > 0) {
            return -GAME_INF;
        }
        return ($aTwo - $bTwo) * DOUBLE_WEIGHT + ($aOne - $bOne);
    }

    /**
     * 计算棋盘各行一二三子数量
     * @param object $state <p>
     * 游戏状态
     * </p>
     * @param int $playerId <p>
     * 选手id
     * </p>
     * @return array 棋盘各行一二三子数量
     */
    public function countPlayerChess(\game\State $state, $playerId)
    {
        global $lineIdxTbl;
        $countOne = $countTwo = $countThree = 0;
        for ($i = 0; $i < LINE_DIRECTION; $i++) {
            $sameCount = 0;
            $empty = 0;
            for ($j = 0; $j < BOARD_COL; $j++) {
                if ($state->GetGameCell($lineIdxTbl[$i][$j]) == $playerId) {
                    $sameCount++;
                }
                if ($state->GetGameCell($lineIdxTbl[$i][$j]) == PLAYER_NULL) {
                    $empty++;
                }
            }
            if (($sameCount == 1) && ($empty == 2)) {
                $countOne++;
            }
            if (($sameCount == 2) && ($empty == 1)) {
                $countTwo++;
            }
            if (($sameCount == 3) && ($empty == 0)) {
                $countThree++;
            }
        }
        return [$countOne, $countTwo, $countThree];
    }

}