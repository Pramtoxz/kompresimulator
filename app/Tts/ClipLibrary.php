<?php

namespace App\Tts;

use App\Enums\Framework;
use App\Enums\StepKey;

class ClipLibrary
{
    /**
     * @return array<int, Clip>
     */
    public static function all(): array
    {
        $clips = [];

        foreach (Framework::cases() as $framework) {
            foreach (StepKey::cases() as $step) {
                foreach (NarrationScript::for($framework, $step) as $index => $text) {
                    $clip = new Clip(NarrationScript::scope($framework, $step), $step->value, $index, $text);
                    $clips[$clip->key()] = $clip;
                }
            }
        }

        foreach (NarrationScript::briefing() as $clip) {
            $clips[$clip->key()] = $clip;
        }

        return array_values($clips);
    }

    public static function path(Clip $clip): string
    {
        return public_path(config('tts.directory').'/'.$clip->fileName());
    }

    public static function manifestPath(): string
    {
        return public_path(config('tts.directory').'/manifest.json');
    }

    /**
     * @return array<string, string>
     */
    public static function manifest(): array
    {
        $path = self::manifestPath();

        if (! is_file($path)) {
            return [];
        }

        $decoded = json_decode((string) file_get_contents($path), true);

        return is_array($decoded) ? array_filter($decoded, 'is_string') : [];
    }

    /**
     * @param  array<string, string>  $manifest
     */
    public static function writeManifest(array $manifest): void
    {
        ksort($manifest);

        file_put_contents(
            self::manifestPath(),
            (string) json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
        );
    }

    public static function url(string $key): ?string
    {
        static $manifest = null;

        if ($manifest === null) {
            $manifest = self::manifest();
        }

        if (! array_key_exists($key, $manifest)) {
            return null;
        }

        return asset(config('tts.directory').'/'.$key.'.m4a');
    }
}
