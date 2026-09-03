<?php

namespace App\Ai\Agents;

use App\Ai\TutorInstructions;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Promptable;

class TutorAgent implements Agent
{
    use Promptable;

    public function instructions(): string
    {
        return TutorInstructions::system();
    }
}
