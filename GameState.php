<?php

class GameState{

    public $m_playerId;
    public $m_board = [];

    function __construct($state = null){
        if($state){
            $this->m_playerId = $state->m_playerId;
            for($i = 0; $i < BOARD_CELLS; $i++){
                $this->m_board[$i] = $state->m_board[$i];
            }
        }
    }

    function SetCurrentPlayer($player_id){
        $this->m_playerId = $player_id;
    }

    function GetCurrentPlayer(){
        return $this->m_playerId;
    }

    function ClearGameCell($cell){
        $this->m_board[$cell] = PLAYER_NULL;
    }

    function GetGameCell($cell){
        return $this->m_board[$cell];
    }

//    const GameState& GameState::operator=(const GameState& state)
//    {
//        if(this != &state)
//        {
//            m_playerId = state.m_playerId;
//            memmove(m_board, state.m_board, BOARD_CELLS * sizeof(int));
//        }
//
//        return *this;
//    }

    function PrintGame(){
        echo "Current game state : ".PHP_EOL;
        for($i = 0; $i < BOARD_CELLS; $i++){
            echo $this->GetCellType($this->m_board[$i]);
            if(($i % BOARD_COL) == 2){
                echo PHP_EOL;
            }
        }
        echo PHP_EOL;
    }

    function InitGameState($firstPlayer){
        for($i = 0; $i < BOARD_CELLS; $i++){
            $this->m_board[$i] = PLAYER_NULL;
        }
        $this->m_playerId = $firstPlayer;
    }

    function SetGameCell($cell, $player_id){
        $this->m_board[$cell] = $player_id;
    }

    function IsEmptyCell($cell){
        return $this->m_board[$cell] == PLAYER_NULL;
    }

    function SwitchPlayer(){
        $this->m_playerId = GetPeerPlayer($this->m_playerId);
    }

    function IsGameOver(){
        if($this->CountEmptyCell() == 0)
            return true;

        $aThree = $this->CountThreeLine($this->m_playerId);
        if($aThree != 0)
            return true;

        $min = GetPeerPlayer($this->m_playerId);
        $bThree = $this->CountThreeLine($min);
        if($bThree != 0)
            return true;

        return false;
    }

    function GetWinner(){
        $aThree = $this->CountThreeLine($this->m_playerId);
        if($aThree != 0)
            return $this->m_playerId;

        $min = GetPeerPlayer($this->m_playerId);
        $bThree = $this->CountThreeLine($min);
        if($bThree != 0)
            return $min;

        return PLAYER_NULL;
    }
    /*
    bool GameState::CountThreeLine(int player_id)
    {
        for(int i = 0; i < LINE_DIRECTION; i++)
        {
            if( (m_board[line_idx_tbl[i][0]] == player_id)
                && (m_board[line_idx_tbl[i][1]] == player_id)
                && (m_board[line_idx_tbl[i][2]] == player_id) )
            {
                return true;
            }
        }

        return false;
    }
    */
    function CountThreeLine($player_id){
        global $line_idx_tbl;
        $lines = 0;
        for($i = 0; $i < LINE_DIRECTION; $i++){
            $sameCount = 0;
            for($j = 0; $j < LINE_CELLS; $j++){
                if($this->m_board[$line_idx_tbl[$i][$j]] == $player_id){
                    $sameCount++;
                }
            }
            if($sameCount == 3){
                $lines++;
            }
        }
        return $lines;
    }

    function CountEmptyCell(){
        $count = 0;
        for($i = 0; $i < BOARD_CELLS; $i++){
            if($this->m_board[$i] == PLAYER_NULL){
                $count++;
            }
        }
        return $count;
    }

    function GetCellType($player_id){
        if($player_id == PLAYER_NULL)
            return CELL_EMPTY;
        else
            return ($player_id == PLAYER_B) ? CELL_X : CELL_O;
    }
}

#if 0
//int GameState::Evaluate(int max_player_id)
//{
//    int min =  GetPeerPlayer(max_player_id);
//
//    int aOne, aTwo, aThree, bOne, bTwo, bThree;
//    CountPlayerChess(max_player_id, aOne, aTwo, aThree);
//    CountPlayerChess(min, bOne, bTwo, bThree);
//
//    if(aThree > 0)
//    {
//        return GAME_INF;
//    }
//    if(bThree > 0)
//    {
//        return -GAME_INF;
//    }
//
//    if(CountEmptyCell() == 0)
//    {
//        return DRAW;
//    }
//
//    return (aTwo - bTwo) * DOUBLE_WEIGHT + (aOne - bOne);
//}
//
//int CGameState::Evaluate()
//{
//    CellType cth = CellTypeFromPlayer(Human);
//    CellType ctc = CellTypeFromPlayer(Computer);
//
//    int cOne, cTwo, cThree, hOne, hTwo, hThree;
//    CountPlayerChess(ctc, cOne, cTwo, cThree);
//    CountPlayerChess(cth, hOne, hTwo, hThree);
//
//    if(cThree > 0)
//    {
//        return GAME_INF;
//    }
//    if(hThree > 0)
//    {
//        return -GAME_INF;
//    }
//
//    if(IsComputerPlayer() && (hTwo > 0))
//    {
//        return -(WIN_LEVEL + (hTwo - cTwo) * DOUBLE_WEIGHT);
//    }
//    if(IsHumanPlayer() && (cTwo > 0))
//    {
//        return WIN_LEVEL + (cTwo - hTwo) * DOUBLE_WEIGHT;
//    }
//
//    if(CountEmptyCell() == 0)
//    {
//        return DRAW;
//    }
//
//    return cOne - hOne;
//}
//
//int CGameState::Evaluate()
//{
//    CellType cth = CellTypeFromPlayer(Human);
//    CellType ctc = CellTypeFromPlayer(Computer);
//
//    int cOne, cTwo, cThree, hOne, hTwo, hThree;
//    CountPlayerChess(ctc, cOne, cTwo, cThree);
//    CountPlayerChess(cth, hOne, hTwo, hThree);
//
//    if(cThree > 0)
//    {
//        return GAME_INF;
//    }
//    if(hThree > 0)
//    {
//        return -GAME_INF;
//    }
//    if(CountEmptyCell() == 0)
//    {
//        return DRAW;
//    }
//
//    return (cTwo * DOUBLE_WEIGHT + cOne) - (hTwo * DOUBLE_WEIGHT - hOne);
//}
#endif