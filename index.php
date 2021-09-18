<?php

require_once 'include/conf.php';
require_once 'include/func.php';
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
$winner = $gc->Run();
if($winner == PLAYER_NULL){
    echo "GameOver, Draw!" . PHP_EOL;
}else{
    $winnerPlayer = $gc->GetPlayer($winner);
    echo "GameOver, " . $winnerPlayer->GetPlayerName() . " Win!" . PHP_EOL;
}