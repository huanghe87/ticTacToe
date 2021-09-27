<?php

namespace player;

class Base{

    public $mPlayerId;
    public $mPlayerName;
    public $mState;

    function __construct($name){
        $this->setPlayerName($name);
    }

    function getPlayerName(){
        return $this->mPlayerName;
    }

    function setPlayerName($name){
        $this->mPlayerName = $name;
    }

    function getPlayerId(){
        return $this->mPlayerId;
    }

    function setPlayerId($id){
        $this->mPlayerId = $id;
    }

    function getGameState(){
        return $this->mState;
    }

    function setGameState(\game\State $state){
        $this->mState = $state;
    }

}