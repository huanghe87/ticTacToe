<?php

namespace game;

/**
 * 游戏控制类
 */
class Control{

    public $mGameState;
    public $mPlayers;

    /**
     * 设置游戏选手的先后手
     * @param object $player <p>
     * 选手对象
     * </p>
     * @param int $playerId <p>
     * 选手id，即先后手
     * </p>
     */
    function setPlayer(\player\Base $player, $playerId){
        $player->SetPlayerId($playerId);
        $player->SetGameState($this->mGameState);
        $this->mPlayers[$playerId] = $player;
    }

    /**
     * 获取选手信息
     * @param int $playerId <p>
     * 选手id
     * </p>
     * @return object 指定选手对象
     */
    function getPlayer($playerId){
        if(isset($this->mPlayers[$playerId])){
            return $this->mPlayers[$playerId];
        }
        return null;
    }

    /**
     * 初始化游戏状态
     * @param object $state <p>
     * 游戏状态
     * </p>
     */
    function initGameState(\game\State $state){
        $this->mGameState = $state;
    }

    /**
     * cli模式下运行游戏
     * @return object 获胜者对象
     */
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

    /**
     * 运行网页游戏
     * @param int $pos <p>
     * 棋子位置，0-8共九个位置
     * </p>
     */
    function runWeb($pos = -1){
        global $playerMap;
        $view = new \MyView();
        $userid = session_id();
        $redis = \MyRedis::getInstance();
        $computerPlay = 0;
        if(($pos >= 0) && $this->mGameState->isEmptyCell($pos)){
            $redis->init()->hSet('user:'.$userid.':board', $pos, $playerMap['human']);
            $computerPlay = 1;
        }else if(!$redis->init()->exists('user:'.$userid.':board')){
            if($playerMap['computer'] == PLAYER_A){
                $computerPlay = 1;
            }
        }
        $board = $redis->init()->hMGet('user:'.$userid.':board', range(0, 8));
        for($i = 0; $i < BOARD_CELLS; $i++){
            $this->mGameState->mBoard[$i] = intval($board[$i]);
        }
        $gameOver = '';
        if($computerPlay){
            if(!$this->mGameState->isGameOver()){
                $this->mGameState->setCurrentPlayer($playerMap['computer']);
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

    /**
     * 清除网页版的棋盘数据
     * @return int 删除成功返回1，失败返回0
     */
    function clearBoard(){
        $userid = session_id();
        $redis = \MyRedis::getInstance();
        return $redis->init()->del('user:'.$userid.':board');
    }

}