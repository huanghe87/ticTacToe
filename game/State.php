<?php

namespace game;

class State{

    public $mPlayerId;
    public $mBoard = [];

    function __construct(State $state = null){
        if($state){
            $this->mPlayerId = $state->mPlayerId;
            for($i = 0; $i < BOARD_CELLS; $i++){
                $this->mBoard[$i] = $state->mBoard[$i];
            }
        }
    }

    function setCurrentPlayer($playerId){
        $this->mPlayerId = $playerId;
    }

    function getCurrentPlayer(){
        return $this->mPlayerId;
    }

    function clearGameCell($cell){
        $this->mBoard[$cell] = PLAYER_NULL;
    }

    function getGameCell($cell){
        return $this->mBoard[$cell];
    }

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

    function initGameState($firstPlayer){
        for($i = 0; $i < BOARD_CELLS; $i++){
            $this->mBoard[$i] = PLAYER_NULL;
        }
        $this->mPlayerId = $firstPlayer;
    }

    function setGameCell($cell, $playerId){
        $this->mBoard[$cell] = $playerId;
    }

    function isEmptyCell($cell){
        return $this->mBoard[$cell] == PLAYER_NULL;
    }

    function switchPlayer(){
        $this->mPlayerId = getPeerPlayer($this->mPlayerId);
    }

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

    function countThreeLine($playerId){
        global $lineIdxTbl;
        $lines = 0;
        for($i = 0; $i < LINE_DIRECTION; $i++){
            $sameCount = 0;
            for($j = 0; $j < LINE_CELLS; $j++){
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

    function countEmptyCell(){
        $count = 0;
        for($i = 0; $i < BOARD_CELLS; $i++){
            if($this->mBoard[$i] == PLAYER_NULL){
                $count++;
            }
        }
        return $count;
    }

    function getCellType($playerId){
        if($playerId == PLAYER_NULL){
            return CELL_EMPTY;
        }else{
            return ($playerId == PLAYER_B) ? CELL_X : CELL_O;
        }
    }
}