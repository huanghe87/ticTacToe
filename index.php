<?php

require_once 'include/loader.php';

$feFunc = new evaluator\Fe();
//$searcher = new searcher\Minimax($feFunc);
//$searcher = new searcher\Negamax($feFunc);
//$searcher = new searcher\AlphaBeta($feFunc);
$searcher = new searcher\NegamaxAlphaBeta($feFunc);
$human = new player\Human("huanghe");
$computer = new player\Computer("xiaogang");
$computer->setSearcher($searcher, SEARCH_DEPTH);
$initState = new game\State();
$initState->initGameState(PLAYER_A);
$gc = new game\Control();
//$initState->setGameCell(8, PLAYER_A);
//$initState->setGameCell(4, PLAYER_B);
//$initState->setGameCell(7, PLAYER_A);
//$initState->setGameCell(6, PLAYER_B);
$gc->initGameState($initState);
$gc->setPlayer($computer, PLAYER_A);
$gc->setPlayer($human, PLAYER_B);
if(PHP_SAPI=='cli'){
    $winner = $gc->run();
    if($winner == PLAYER_NULL){
        echo "GameOver, Draw!" . PHP_EOL;
    }else{
        $winnerPlayer = $gc->getPlayer($winner);
        echo "GameOver, " . $winnerPlayer->getPlayerName() . " Win!" . PHP_EOL;
    }
}else{
    $type = isset($_REQUEST['type'])?trim($_REQUEST['type']):'0';
    $pos = isset($_REQUEST['pos'])?trim($_REQUEST['pos']):'-1';
    if($type == 1){
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