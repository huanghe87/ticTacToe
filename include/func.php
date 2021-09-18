<?php

function GetPeerPlayer($player_id){
    return ($player_id == PLAYER_A) ? PLAYER_B : PLAYER_A;
}