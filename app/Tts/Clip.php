<?php

namespace App\Tts;

class Clip
{
    public function __construct(
        public string $scope,
        public string $step,
        public int $index,
        public string $text,
    ) {}

    public function key(): string
    {
        return $this->scope.'/'.$this->step.'/'.$this->index;
    }

    public function fileName(): string
    {
        return $this->key().'.m4a';
    }

    public function hash(): string
    {
        return substr(sha1($this->text), 0, 12);
    }
}
