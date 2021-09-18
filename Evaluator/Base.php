<?php

namespace Evaluator;

interface Base{

    public function Evaluate(\Game\State $state, $player_id);

}