<?php

namespace game;

/**
 * 棋局状态类
 */
class State{

    //选手
    public $mPlayerId;
    //棋盘
    public $mBoard = [];

    /**
     * 构造方法
     */
    function __construct(State $state = null){
        if($state){
            $this->mPlayerId = $state->mPlayerId;
            for($i = 0; $i < BOARD_CELLS; $i++){
                $this->mBoard[$i] = $state->mBoard[$i];
            }
        }
    }

    /**
     * 设置当前玩家
     */
    function setCurrentPlayer($playerId){
        $this->mPlayerId = $playerId;
    }

    /**
     * 获取当前玩家
     */
    function getCurrentPlayer(){
        return $this->mPlayerId;
    }

    /**
     * 移除指定位置棋子
     */
    function clearGameCell($cell){
        $this->mBoard[$cell] = PLAYER_NULL;
    }

    /**
     * 获取指定位置棋子
     * @return int 空，先手，后手
     */
    function getGameCell($cell){
        return $this->mBoard[$cell];
    }

    /**
     * 打印当前棋局
     */
    function printGame(){
        echo "Current game state : ".PHP_EOL;
        for($i = 0; $i < BOARD_CELLS; $i++){
            echo $this->getCellType($this->mBoard[$i]);
            if(($i % BOARD_COL) == 2){
                echo PHP_EOL;
            }
        }
        echo PHP_EOL;
    }

    /**
     * 初始化棋局，即棋子和下棋人
     */
    function initGameState($firstPlayer){
        for($i = 0; $i < BOARD_CELLS; $i++){
            $this->mBoard[$i] = PLAYER_NULL;
        }
        $this->mPlayerId = $firstPlayer;
    }

    /**
     * 指定位置走棋
     */
    function setGameCell($cell, $playerId){
        $this->mBoard[$cell] = $playerId;
    }

    /**
     * 指定位置是否已落子
     */
    function isEmptyCell($cell){
        return $this->mBoard[$cell] == PLAYER_NULL;
    }

    /**
     * 切换棋手下棋
     */
    function switchPlayer(){
        $this->mPlayerId = getPeerPlayer($this->mPlayerId);
    }

    /**
     * 棋局是否已决胜负
     */
    function isGameOver(){
        if($this->countEmptyCell() == 0){
            return true;
        }
        $aThree = $this->countThreeLine($this->mPlayerId);
        if($aThree != 0){
            return true;
        }
        $min = getPeerPlayer($this->mPlayerId);
        $bThree = $this->countThreeLine($min);
        if($bThree != 0){
            return true;
        }
        return false;
    }

    /**
     * 获取本局获胜者
     */
    function getWinner(){
        $aThree = $this->countThreeLine($this->mPlayerId);
        if($aThree != 0){
            return $this->mPlayerId;
        }
        $min = getPeerPlayer($this->mPlayerId);
        $bThree = $this->countThreeLine($min);
        if($bThree != 0){
            return $min;
        }
        return PLAYER_NULL;
    }

    /**
     * 计算行，列，对角线是否有三连子
     */
    function countThreeLine($playerId){
        global $lineIdxTbl;
        $lines = 0;
        for($i = 0; $i < LINE_DIRECTION; $i++){
            $sameCount = 0;
            for($j = 0; $j < BOARD_COL; $j++){
                if($this->mBoard[$lineIdxTbl[$i][$j]] == $playerId){
                    $sameCount++;
                }
            }
            if($sameCount == 3){
                $lines++;
            }
        }
        return $lines;
    }

    /**
     * 计算棋盘未落子位置的数量
     */
    function countEmptyCell(){
        $count = 0;
        for($i = 0; $i < BOARD_CELLS; $i++){
            if($this->mBoard[$i] == PLAYER_NULL){
                $count++;
            }
        }
        return $count;
    }

    /**
     * 获取棋手用的棋子类型，是先手棋子，还是后手棋子
     */
    function getCellType($playerId){
        if($playerId == PLAYER_NULL){
            return CELL_EMPTY;
        }else{
            return ($playerId == PLAYER_B) ? CELL_X : CELL_O;
        }
    }
}