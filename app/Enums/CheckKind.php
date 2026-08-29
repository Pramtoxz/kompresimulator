<?php

namespace App\Enums;

enum CheckKind: string
{
    case Structure = 'structure';
    case Database = 'db';
    case Calculation = 'calc';
}
