<?php

namespace App\Tts;

use Illuminate\Support\Facades\Process;
use RuntimeException;

class AudioEncoder
{
    public function write(SpokenAudio $audio, string $destination): string
    {
        $directory = dirname($destination);

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $binary = $this->binary();

        if ($binary === null) {
            $fallback = preg_replace('/\.m4a$/', '.wav', $destination) ?? $destination;
            file_put_contents($fallback, $this->wav($audio));

            return $fallback;
        }

        $source = $directory.'/'.basename($destination, '.m4a').'.pcm';
        file_put_contents($source, $audio->pcm);

        $result = Process::timeout(120)->run([
            $binary,
            '-hide_banner', '-loglevel', 'error', '-y',
            '-f', 's16le',
            '-ar', (string) $audio->sampleRate,
            '-ac', '1',
            '-i', $source,
            '-c:a', 'aac',
            '-b:a', (string) config('tts.bitrate'),
            $destination,
        ]);

        unlink($source);

        if ($result->failed()) {
            throw new RuntimeException('ffmpeg gagal mengubah audio: '.$result->errorOutput());
        }

        return $destination;
    }

    public function binary(): ?string
    {
        $configured = config('tts.ffmpeg');

        if (is_string($configured) && $configured !== '' && is_file($configured)) {
            return $configured;
        }

        return Process::run(['ffmpeg', '-version'])->successful() ? 'ffmpeg' : null;
    }

    private function wav(SpokenAudio $audio): string
    {
        $size = strlen($audio->pcm);
        $byteRate = $audio->sampleRate * 2;

        return 'RIFF'
            .pack('V', 36 + $size)
            .'WAVEfmt '
            .pack('V', 16)
            .pack('v', 1)
            .pack('v', 1)
            .pack('V', $audio->sampleRate)
            .pack('V', $byteRate)
            .pack('v', 2)
            .pack('v', 16)
            .'data'
            .pack('V', $size)
            .$audio->pcm;
    }
}
