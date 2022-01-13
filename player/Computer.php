<?php

namespace player;

/**
 * 机器玩家类
 */
class computer extends Base
{

    //采用的搜索算法
    public $mSearcher;

    //搜索深度
    public $mDepth;

    /**
     * 构造方法
     */
    function __construct($name)
    {
        parent::__construct($name);
        $this->mSearcher = null;
        $this->mDepth = 3;
    }

    /**
     * 获取机器落子位置
     */
    function getNextPosition()
    {
        if ($this->mState == null) {
            exit('ComputerPlayer的m_state不能为null' . PHP_EOL);
        }
        if ($this->mSearcher == null) {
            exit('ComputerPlayer的m_searcher不能为null' . PHP_EOL);
        }
        $np = $this->mSearcher->searchBestPlay($this->mState, $this->mDepth);
        if ($np < 0) {
            echo $this->getPlayerName() . " search fail" . PHP_EOL;
            exit;
        }
        $row = intval($np / BOARD_COL);
        $col = $np % BOARD_COL;
        printMsg($this->getPlayerName() . " play at [" . ($row + 1) . " , " . ($col + 1) . "]" . PHP_EOL);
        return $np;
    }

    /**
     * 设置机器采用的搜索算法和搜索深度
     */
    function setSearcher($searcher, $depth)
    {
        $tmp = $this->mSearcher;
        $this->mSearcher = $searcher;
        $this->mDepth = $depth;
        return $tmp;
    }

}