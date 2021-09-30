<?php

//棋盘行数
const BOARD_ROW = 3;

//棋盘列数
const BOARD_COL = 3;

//棋盘一共多少格，即多少位置可以落子
const BOARD_CELLS = BOARD_ROW * BOARD_COL;

//获胜局面得分
const GAME_INF = 100;

//双子得分
const DOUBLE_WEIGHT = 10;

//未落子显示的字符
const CELL_EMPTY = '-';

//先手棋子显示的字符
const CELL_O = 'o';

//后手棋子显示的字符
const CELL_X = 'x';

//未落子类型
const PLAYER_NULL = 0;

//先手棋子类型
const PLAYER_A = 1;

//后手棋子类型
const PLAYER_B = 2;

//博弈树搜索深度
const SEARCH_DEPTH = 9;

//获胜的八种情况，三行三列两斜线
const LINE_DIRECTION = 8;

//获胜的八种情况，三行三列两斜线
$lineIdxTbl = [
    [0, 1, 2], //第一行
    [3, 4, 5], //第二行
    [6, 7, 8], //第三行
    [0, 3, 6], //第一列
    [1, 4, 7], //第二列
    [2, 5, 8], //第三列
    [0, 4, 8], //正交叉线
    [2, 4, 6], //反交叉线
];