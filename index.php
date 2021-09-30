<?php

//引入加载器
require_once 'include/loader.php';

//声明评估函数
$feFunc = new evaluator\Fe();

//$searcher = new searcher\Minimax($feFunc);
//$searcher = new searcher\Negamax($feFunc);
//$searcher = new searcher\AlphaBeta($feFunc);
$searcher = new searcher\NegamaxAlphaBeta($feFunc);

//声明人类玩家
$human = new player\Human("huanghe");

//声明机器玩家
$computer = new player\Computer("xiaogang");
//设置机器搜索深度
$computer->setSearcher($searcher, SEARCH_DEPTH);

//声明棋局状态
$initState = new game\State();
//初始化当前棋局的下棋选手
$initState->initGameState(PLAYER_A);

//声明游戏控制类
$gc = new game\Control();
//$initState->setGameCell(8, PLAYER_A);
//$initState->setGameCell(4, PLAYER_B);
//$initState->setGameCell(7, PLAYER_A);
//$initState->setGameCell(6, PLAYER_B);
$gc->initGameState($initState);

//设置棋局对弈双方
//$playerMap = [
//    'computer' => PLAYER_A,
//    'human' => PLAYER_B
//];
$playerMap = [
    'human' => PLAYER_A,
    'computer' => PLAYER_B
];
foreach($playerMap as $player => $playerType){
    $gc->setPlayer($$player, $playerType);
}

if(PHP_SAPI=='cli'){
    //命令行下运行
    $winner = $gc->run();
    if($winner == PLAYER_NULL){
        echo "GameOver, Draw!" . PHP_EOL;
    }else{
        $winnerPlayer = $gc->getPlayer($winner);
        echo "GameOver, " . $winnerPlayer->getPlayerName() . " Win!" . PHP_EOL;
    }
}else{
    //网页运行
    $type = isset($_REQUEST['type'])?trim($_REQUEST['type']):'0';
    $pos = isset($_REQUEST['pos'])?trim($_REQUEST['pos']):'-1';
    if($type == 1){
        //重玩
        $gc->clearBoard();
        header('Location: /ticTacToe/index.php');
    }else{
        $pos = intval($pos);
        if(!in_array($pos, range(0, 8))){
            $pos = -1;
        }
        $gc->runWeb($pos);
    }
}