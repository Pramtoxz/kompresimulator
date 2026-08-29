<?php

namespace App\Practice\Checks;

use App\Enums\CheckKind;

class CheckOutcome
{
    /**
     * @param  array<string, mixed>|null  $actual
     */
    public function __construct(
        public readonly CheckKind $kind,
        public readonly bool $passed,
        public readonly string $message,
        public readonly ?int $testCaseId = null,
        public readonly ?array $actual = null,
    ) {}
}
