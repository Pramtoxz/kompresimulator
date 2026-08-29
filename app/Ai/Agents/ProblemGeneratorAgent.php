<?php

namespace App\Ai\Agents;

use App\Ai\ProblemInstructions;
use App\Ai\ProblemSchema;
use App\Enums\Framework;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;

class ProblemGeneratorAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function __construct(private Framework $framework) {}

    public function instructions(): string
    {
        return ProblemInstructions::for($this->framework);
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return ProblemSchema::definition($schema);
    }
}
