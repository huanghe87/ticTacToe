<?php

function GetPeerPlayer($player_id){
    return ($player_id == PLAYER_A) ? PLAYER_B : PLAYER_A;
}

function printMsg($msg){
    if(PHP_SAPI=='cli'){
        echo $msg;
    }
}