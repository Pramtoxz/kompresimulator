<?php

namespace App\Enums;

enum ProblemStatus: string
{
    case Queued = 'queued';
    case Ready = 'ready';
    case Failed = 'failed';
}
