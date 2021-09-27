<?php

namespace searcher;

abstract class Base{

    public $mEvaluator;

    public function __construct($evaluator){
        $this->mEvaluator = $evaluator;
    }

    abstract public function searchBestPlay(\game\State $state, $depth);

}