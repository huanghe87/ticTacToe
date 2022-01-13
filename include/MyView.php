<?php

/**
 * 视图类，网页模式用
 */
class MyView
{

    public $var = [];

    public function __construct()
    {

    }

    public function assign($key, $value)
    {
        $this->var[$key] = $value;
    }

    public function display($fileName = 'index')
    {
        extract($this->var);
        require_once __DIR__ . '/../view/' . $fileName . '.php';
    }

}