<?php

namespace App\Ai\Providers;

use App\Ai\Gateway\OpenAiCompatibleGateway;
use Laravel\Ai\Contracts\Gateway\StepTextGateway;
use Laravel\Ai\Providers\OpenAiCompatibleProvider as BaseProvider;

class OpenAiCompatibleProvider extends BaseProvider
{
    public function textGateway(): StepTextGateway
    {
        return $this->textGateway ??= new OpenAiCompatibleGateway($this->events);
    }
}
