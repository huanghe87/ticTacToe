<?php

const BOARD_ROW = 3;
const BOARD_COL = 3;
const BOARD_CELLS = BOARD_ROW * BOARD_COL;

const GAME_INF = 100;
//const WIN_LEVEL = 80;
//const DRAW = 0;
const DOUBLE_WEIGHT = 10;

const CELL_EMPTY = '-';
const CELL_O = 'o';
const CELL_X = 'x';

const PLAYER_NULL = 0;
const PLAYER_A = 1;
const PLAYER_B = 2;

const LINE_DIRECTION = 8;
const LINE_CELLS = 3;

const SEARCH_DEPTH = 6;

$line_idx_tbl = [
    [0, 1, 2], //��һ��
    [3, 4, 5], //�ڶ���
    [6, 7, 8], //������
    [0, 3, 6], //��һ��
    [1, 4, 7], //�ڶ���
    [2, 5, 8], //������
    [0, 4, 8], //��������
    [2, 4, 6], //��������
];

function GetPeerPlayer($player_id){
    return ($player_id == PLAYER_A) ? PLAYER_B : PLAYER_A;
}

spl_autoload_register(function ($class) {
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $classFile =  __DIR__ . '/'.$class . '.php';
    if (is_file($classFile)) {
        require_once($classFile);
    }
    return true;
});

function main(){
    $feFunc = new FeEvaluator();
    $nabs = new NegamaxAlphaBetaSearcher($feFunc);
    $human = new HumanPlayer("huanghe");
    $computer = new ComputerPlayer("xiaogang");
    $computer->SetSearcher($nabs, SEARCH_DEPTH);
    $init_state = new GameState();
    $init_state->InitGameState(PLAYER_A);
    $gc = new GameControl();
    $gc->InitGameState($init_state);
    $gc->SetPlayer($computer, PLAYER_A);
    $gc->SetPlayer($human, PLAYER_B);
    $winner = $gc->Run();
    if($winner == PLAYER_NULL){
        echo "GameOver, Draw!" . PHP_EOL;
    }else{
        $winnerPlayer = $gc->GetPlayer($winner);
        echo "GameOver, " . $winnerPlayer->GetPlayerName() . " Win!" . PHP_EOL;
    }
}

main();