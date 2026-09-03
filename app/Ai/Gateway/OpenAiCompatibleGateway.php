<?php

namespace App\Ai\Gateway;

use Laravel\Ai\Gateway\OpenAiCompatible\OpenAiCompatibleGateway as BaseGateway;
use Laravel\Ai\Gateway\StepContext;
use Laravel\Ai\Gateway\TextGenerationOptions;
use Laravel\Ai\ObjectSchema;
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
        if (filled($schema)) {
            $schemaJson = json_encode((new ObjectSchema($schema))->toSchema(), JSON_PRETTY_PRINT);
            $instructions = trim(($instructions ?? '')."\n\nPENTING: Kamu WAJIB menjawab HANYA dalam format JSON valid (RFC 8259) yang mematuhi skema berikut tanpa teks pembuka atau penutup apapun:\n".$schemaJson);
        }

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
