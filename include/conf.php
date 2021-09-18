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
    [0, 1, 2], //第一行
    [3, 4, 5], //第二行
    [6, 7, 8], //第三行
    [0, 3, 6], //第一列
    [1, 4, 7], //第二列
    [2, 5, 8], //第三列
    [0, 4, 8], //正交叉线
    [2, 4, 6], //反交叉线
];