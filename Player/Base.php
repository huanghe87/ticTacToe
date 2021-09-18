<?php

namespace Player;

class Base{

    public $m_playerId;
    public $m_playerName;
    public $m_state;

    function __construct($name){
        $this->SetPlayerName($name);
    }

    function GetPlayerName(){
        return $this->m_playerName;
    }

    function SetPlayerName($name){
        $this->m_playerName = $name;
    }

    function GetPlayerId(){
        return $this->m_playerId;
    }

    function SetPlayerId($id){
        $this->m_playerId = $id;
    }

    function GetGameState(){
        return $this->m_state;
    }

    function SetGameState(\Game\State $state){
        $this->m_state = $state;
    }

}