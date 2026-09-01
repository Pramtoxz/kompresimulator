<?php

namespace App\Guides;

class StepCard
{
    public function __construct(
        public string $title,
        public string $instruction,
        public ?string $code = null,
        public string $language = 'php',
        public ?string $note = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'instruction' => $this->instruction,
            'code' => $this->code,
            'language' => $this->language,
            'note' => $this->note,
        ];
    }
}
