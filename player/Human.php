<?php

namespace player;

/**
 * 人类玩家类
 */
class Human extends Base
{

    /**
     * 获取人类玩家落子位置
     */
    function getNextPosition()
    {
        if ($this->mState == null) {
            exit('HumanPlayer的m_state不能为null');
        }
        $np = 0;
        while (true) {
            echo "Please select your position (row = 1-3,col = 1-3): ";
            $inputStr = trim(fgets(STDIN));
            $inputArr = explode(',', $inputStr);
            if (count($inputArr) != 2) {
                echo "input error" . PHP_EOL;
                continue;
            }
            foreach ($inputArr as $k => &$v) {
                $v = intval($v);
                if (($v < 1) || ($v > 3)) {
                    if ($k == 0) {
                        echo "row input error" . PHP_EOL;
                    } else {
                        echo "col input error" . PHP_EOL;
                    }
                    continue;
                }
            }
            $row = $inputArr[0];
            $col = $inputArr[1];
            $np = ($row - 1) * BOARD_COL + ($col - 1);
            if ((($np >= 0) && ($np < 9))
                && $this->mState->isEmptyCell($np)) {
                echo $this->getPlayerName() . " play at [" . ($row + 1) . " , " . ($col + 1) . "]" . PHP_EOL;
                break;
            } else {
                echo "Invalid position on (" . $row . " , " . $col . ")" . PHP_EOL;
            }
        }
        return $np;
    }
}