<?php

namespace player;

/**
 * 玩家基类
 */
class Base{

    //玩家id
    public $mPlayerId;

    //玩家名称
    public $mPlayerName;

    //当前棋局状态
    public $mState;

    /**
     * 构造方法，设置玩家名称
     */
    function __construct($name){
        $this->setPlayerName($name);
    }

    /**
     * 获取玩家名称
     */
    function getPlayerName(){
        return $this->mPlayerName;
    }

    /**
     * 设置玩家名称
     */
    function setPlayerName($name){
        $this->mPlayerName = $name;
    }

    /**
     * 获取玩家id
     */
    function getPlayerId(){
        return $this->mPlayerId;
    }

    /**
     * 设置玩家id
     */
    function setPlayerId($id){
        $this->mPlayerId = $id;
    }

    /**
     * 获取棋局状态
     */
    function getGameState(){
        return $this->mState;
    }

    /**
     * 设置棋局状态
     */
    function setGameState(\game\State $state){
        $this->mState = $state;
    }

}