<?php

class GameControl{

    public $m_gameState;
    public $m_players;

    function SetPlayer(Player $player, $player_id){
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

    function InitGameState(GameState $state){
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

}