<?php

namespace Game;

class Control{

    public $m_gameState;
    public $m_players;

    function SetPlayer(\Player\Base $player, $player_id){
        $player->SetPlayerId($player_id);
        $player->SetGameState($this->m_gameState);
        $this->m_players[$player_id] = $player;
    }

    function GetPlayer($player_id){
        if(isset($this->m_players[$player_id])){
            return $this->m_players[$player_id];
        }
        return NULL;
    }

    function InitGameState(\Game\State $state){
        $this->m_gameState = $state;
    }

    function Run(){
        while(!$this->m_gameState->IsGameOver()){
            $playerId = $this->m_gameState->GetCurrentPlayer();
            $currentPlayer = $this->GetPlayer($playerId);
            if($currentPlayer == NULL){
                exit('GameControl的currentPlayer不能为null'.PHP_EOL);
            }
            $np = $currentPlayer->GetNextPosition();
            $this->m_gameState->SetGameCell($np, $playerId);
            $this->m_gameState->PrintGame();
            $this->m_gameState->SwitchPlayer();
        }
        $winner = $this->m_gameState->GetWinner();
        return $winner;
    }

    function RunWeb($pos = -1){
        $view = new \MyView();
        $userid = session_id();
        $redis = \MyRedis::getInstance();
        $computerPlay = 0;
        if(($pos >= 0) && $this->m_gameState->IsEmptyCell($pos)){
            $redis->init()->hSet('user:'.$userid.':board', $pos, PLAYER_B);
            $computerPlay = 1;
        }else if(!$redis->init()->exists('user:'.$userid.':board')){
            $computerPlay = 1;
        }
        $board = $redis->init()->hMGet('user:'.$userid.':board', range(0, 8));
        for($i = 0; $i < BOARD_CELLS; $i++){
            $this->m_gameState->m_board[$i] = intval($board[$i]);
        }
        $gameOver = '';
        if($computerPlay){
            if(!$this->m_gameState->IsGameOver()){
                $playerId = $this->m_gameState->GetCurrentPlayer();
                $currentPlayer = $this->GetPlayer($playerId);
                if($currentPlayer == NULL){
                    exit('GameControl的currentPlayer不能为null'.PHP_EOL);
                }
                $np = $currentPlayer->GetNextPosition();
                $this->m_gameState->SetGameCell($np, $playerId);
                $redis->init()->hSet('user:'.$userid.':board', $np, $playerId);
            }
            if($this->m_gameState->IsGameOver()){
                $winner = $this->m_gameState->GetWinner();
                if($winner == PLAYER_NULL){
                    $gameOver = "GameOver, Draw!";
                }else{
                    $winnerPlayer = $this->GetPlayer($winner);
                    $gameOver = "GameOver, " . $winnerPlayer->GetPlayerName() . " Win!";
                }
            }
        }
        $view->assign('board', $this->m_gameState->m_board);
        $view->assign('gameOver', $gameOver);
        $view->display();
    }

    function clearBoard(){
        $userid = session_id();
        $redis = \MyRedis::getInstance();
        return $redis->init()->del('user:'.$userid.':board');
    }

}