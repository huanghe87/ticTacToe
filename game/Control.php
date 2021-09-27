<?php

namespace game;

class Control{

    public $mGameState;
    public $mPlayers;

    function setPlayer(\player\Base $player, $playerId){
        $player->SetPlayerId($playerId);
        $player->SetGameState($this->mGameState);
        $this->mPlayers[$playerId] = $player;
    }

    function getPlayer($playerId){
        if(isset($this->mPlayers[$playerId])){
            return $this->mPlayers[$playerId];
        }
        return null;
    }

    function initGameState(\game\State $state){
        $this->mGameState = $state;
    }

    function run(){
        while(!$this->mGameState->isGameOver()){
            $playerId = $this->mGameState->getCurrentPlayer();
            $currentPlayer = $this->getPlayer($playerId);
            if($currentPlayer == null){
                exit('GameControl的currentPlayer不能为null'.PHP_EOL);
            }
            $np = $currentPlayer->getNextPosition();
            $this->mGameState->setGameCell($np, $playerId);
            $this->mGameState->printGame();
            $this->mGameState->switchPlayer();
        }
        $winner = $this->mGameState->getWinner();
        return $winner;
    }

    function runWeb($pos = -1){
        $view = new \MyView();
        $userid = session_id();
        $redis = \MyRedis::getInstance();
        $computerPlay = 0;
        if(($pos >= 0) && $this->mGameState->isEmptyCell($pos)){
            $redis->init()->hSet('user:'.$userid.':board', $pos, PLAYER_B);
            $computerPlay = 1;
        }else if(!$redis->init()->exists('user:'.$userid.':board')){
            $computerPlay = 1;
        }
        $board = $redis->init()->hMGet('user:'.$userid.':board', range(0, 8));
        for($i = 0; $i < BOARD_CELLS; $i++){
            $this->mGameState->mBoard[$i] = intval($board[$i]);
        }
        $gameOver = '';
        if($computerPlay){
            if(!$this->mGameState->isGameOver()){
                $playerId = $this->mGameState->getCurrentPlayer();
                $currentPlayer = $this->getPlayer($playerId);
                if($currentPlayer == null){
                    exit('GameControl的currentPlayer不能为null'.PHP_EOL);
                }
                $np = $currentPlayer->getNextPosition();
                $this->mGameState->setGameCell($np, $playerId);
                $redis->init()->hSet('user:'.$userid.':board', $np, $playerId);
            }
            if($this->mGameState->isGameOver()){
                $winner = $this->mGameState->getWinner();
                if($winner == PLAYER_NULL){
                    $gameOver = "GameOver, Draw!";
                }else{
                    $winnerPlayer = $this->getPlayer($winner);
                    $gameOver = "GameOver, " . $winnerPlayer->getPlayerName() . " Win!";
                }
            }
        }
        $view->assign('board', $this->mGameState->mBoard);
        $view->assign('gameOver', $gameOver);
        $view->display();
    }

    function clearBoard(){
        $userid = session_id();
        $redis = \MyRedis::getInstance();
        return $redis->init()->del('user:'.$userid.':board');
    }

}