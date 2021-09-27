<?php

namespace evaluator;

interface Base{

    public function evaluate(\Game\State $state, $player_id);

}