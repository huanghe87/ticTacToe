<?php

require_once 'include/loader.php';

$feFunc = new Evaluator\Fe();
//$ms = new Searcher\Minimax($feFunc);
//$ns = new Searcher\Negamax($feFunc);
//$abs = new Searcher\AlphaBeta($feFunc);
$nabs = new Searcher\NegamaxAlphaBeta($feFunc);
$human = new Player\Human("huanghe");
$computer = new Player\Computer("xiaogang");
$computer->SetSearcher($nabs, SEARCH_DEPTH);
$initState = new Game\State();
$initState->InitGameState(PLAYER_A);
$gc = new Game\Control();
$gc->InitGameState($initState);
$gc->SetPlayer($computer, PLAYER_A);
$gc->SetPlayer($human, PLAYER_B);
if(PHP_SAPI=='cli'){
    $winner = $gc->Run();
    if($winner == PLAYER_NULL){
        echo "GameOver, Draw!" . PHP_EOL;
    }else{
        $winnerPlayer = $gc->GetPlayer($winner);
        echo "GameOver, " . $winnerPlayer->GetPlayerName() . " Win!" . PHP_EOL;
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
        $gc->RunWeb($pos);
    }
}