<?php

namespace App\Ai\Gateway;

use Laravel\Ai\Gateway\OpenAiCompatible\OpenAiCompatibleGateway as BaseGateway;
use Laravel\Ai\Gateway\StepContext;
use Laravel\Ai\Gateway\TextGenerationOptions;
use Laravel\Ai\Providers\Provider;

class OpenAiCompatibleGateway extends BaseGateway
{
    /**
     * @param  array<array-key, mixed>  $messages
     * @param  array<array-key, mixed>  $tools
     * @param  array<array-key, mixed>|null  $schema
     * @return array<string, mixed>
     */
    protected function buildStepBody(
        Provider $provider,
        string $model,
        ?string $instructions,
        array $messages,
        array $tools,
        ?array $schema,
        ?TextGenerationOptions $options,
        StepContext $stepContext,
    ): array {
        $body = parent::buildStepBody(
            $provider,
            $model,
            $instructions,
            $messages,
            $tools,
            $schema,
            $options,
            $stepContext,
        );

        $body['stream'] = false;

        return $body;
    }
}
