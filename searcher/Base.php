<?php

namespace searcher;

/**
 * 搜索算法抽象类
 */
abstract class Base
{

    public $mEvaluator;

    /**
     * 构造方法，初始化评估函数
     */
    public function __construct($evaluator)
    {
        $this->mEvaluator = $evaluator;
    }

    /**
     * 落子搜索方法
     */
    abstract public function searchBestPlay(\game\State $state, $depth);

}