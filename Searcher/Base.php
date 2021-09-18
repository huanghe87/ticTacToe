<?php

namespace Searcher;

abstract class Base{

    public $m_evaluator;

    public function __construct($evaluator){
        $this->m_evaluator = $evaluator;
    }

    abstract public function SearchBestPlay(\Game\State $state, $depth);

}