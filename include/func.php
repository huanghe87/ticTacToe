<?php

function getPeerPlayer($playerId){
    return ($playerId == PLAYER_A) ? PLAYER_B : PLAYER_A;
}

function printMsg($msg){
    if(PHP_SAPI=='cli'){
        echo $msg;
    }
}