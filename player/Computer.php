<?php

namespace player;

class computer extends Base{

    public $mSearcher;
    public $mDepth;

    function __construct($name){
        parent::__construct($name);
        $this->mSearcher = null;
        $this->mDepth = 3;
    }

    function getNextPosition(){
        if($this->mState == null){
            exit('ComputerPlayer的m_state不能为null'.PHP_EOL);
        }
        if($this->mSearcher == null){
            exit('ComputerPlayer的m_searcher不能为null'.PHP_EOL);
        }
        $np = $this->mSearcher->searchBestPlay($this->mState, $this->mDepth);
        if($np < 0){
            echo $this->getPlayerName() . " search fail" . PHP_EOL;
            exit;
        }
        $row = intval($np / BOARD_COL);
        $col = $np % BOARD_COL;
        printMsg($this->getPlayerName() . " play at [" . ($row + 1) . " , " . ($col + 1) . "]" . PHP_EOL);
        return $np;
    }

    function setSearcher($searcher, $depth){
        $tmp = $this->mSearcher;
        $this->mSearcher = $searcher;
        $this->mDepth = $depth;
        return $tmp;
    }

}