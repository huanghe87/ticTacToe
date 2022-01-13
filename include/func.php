<?php

/**
 * 切换棋手
 */
function getPeerPlayer($playerId)
{
    return ($playerId == PLAYER_A) ? PLAYER_B : PLAYER_A;
}

/**
 * cli模式下打印信息
 */
function printMsg($msg)
{
    if (PHP_SAPI == 'cli') {
        echo $msg;
    }
}