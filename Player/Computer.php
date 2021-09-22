<?php

namespace Player;

class Computer extends Base{

    public $m_searcher;
    public $m_depth;

    function __construct($name){
        parent::__construct($name);
        $this->m_searcher = NULL;
        $this->m_depth = 3;
    }

    function GetNextPosition(){
        if($this->m_state == NULL){
            exit('ComputerPlayer的m_state不能为null'.PHP_EOL);
        }
        if($this->m_searcher == NULL){
            exit('ComputerPlayer的m_searcher不能为null'.PHP_EOL);
        }
        $np = $this->m_searcher->SearchBestPlay($this->m_state, $this->m_depth);
        if($np < 0){
            echo $this->GetPlayerName() . " search fail" . PHP_EOL;
            exit;
        }
        $row = intval($np / BOARD_COL);
        $col = $np % BOARD_COL;
        printMsg($this->GetPlayerName() . " play at [" . ($row + 1) . " , " . ($col + 1) . "]" . PHP_EOL);
        return $np;
    }

    function SetSearcher($searcher, $depth){
        $tmp = $this->m_searcher;
        $this->m_searcher = $searcher;
        $this->m_depth = $depth;
        return $tmp;
    }

}