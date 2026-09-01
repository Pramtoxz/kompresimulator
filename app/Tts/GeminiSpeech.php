<?php

namespace App\Tts;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class GeminiSpeech
{
    public function synthesize(string $text): SpokenAudio
    {
        $key = config('ai.providers.gemini.key');

        if (! is_string($key) || $key === '') {
            throw new RuntimeException('GEMINI_API_KEY belum diisi di .env.');
        }

        $response = Http::timeout((int) config('tts.timeout'))
            ->post($this->endpoint($key), [
                'contents' => [['parts' => [['text' => $this->prompt($text)]]]],
                'generationConfig' => [
                    'responseModalities' => ['AUDIO'],
                    'speechConfig' => [
                        'voiceConfig' => [
                            'prebuiltVoiceConfig' => ['voiceName' => config('tts.voice')],
                        ],
                    ],
                ],
            ]);

        if ($response->failed()) {
            throw new RuntimeException('Gemini menolak permintaan: HTTP '.$response->status().' '.$response->body());
        }

        $part = $response->json('candidates.0.content.parts.0.inlineData');

        if (! is_array($part) || ! is_string($part['data'] ?? null)) {
            throw new RuntimeException('Balasan Gemini tidak berisi audio.');
        }

        $pcm = base64_decode($part['data'], true);

        if ($pcm === false || $pcm === '') {
            throw new RuntimeException('Audio dari Gemini tidak bisa dibaca.');
        }

        return new SpokenAudio($pcm, $this->sampleRate(is_string($part['mimeType'] ?? null) ? $part['mimeType'] : ''));
    }

    private function prompt(string $text): string
    {
        $style = config('tts.style');

        return is_string($style) && $style !== ''
            ? $style.': '.$text
            : $text;
    }

    private function endpoint(string $key): string
    {
        return 'https://generativelanguage.googleapis.com/v1beta/models/'
            .config('tts.model').':generateContent?key='.$key;
    }

    private function sampleRate(string $mimeType): int
    {
        if (preg_match('/rate=(\d+)/i', $mimeType, $match) === 1) {
            return (int) $match[1];
        }

        return 24000;
    }
}
