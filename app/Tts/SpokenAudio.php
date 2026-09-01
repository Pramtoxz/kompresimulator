<?php

namespace App\Tts;

class SpokenAudio
{
    public function __construct(
        public string $pcm,
        public int $sampleRate,
    ) {}

    public function seconds(): float
    {
        return strlen($this->pcm) / ($this->sampleRate * 2);
    }
}
