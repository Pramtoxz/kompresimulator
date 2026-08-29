<?php

namespace App\Enums;

enum AttemptStatus: string
{
    case Running = 'running';
    case Finished = 'finished';
    case Abandoned = 'abandoned';
}
