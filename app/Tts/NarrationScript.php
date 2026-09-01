<?php

namespace App\Tts;

use App\Enums\Framework;
use App\Enums\StepKey;
use App\Tts\Script\BriefingScript;
use App\Tts\Script\InstallScript;
use App\Tts\Script\MigrationNarration;
use App\Tts\Script\SharedScript;

class NarrationScript
{
    /**
     * @return array<int, string>
     */
    public static function for(Framework $framework, StepKey $step): array
    {
        return match ($step) {
            StepKey::Install => InstallScript::for($framework),
            StepKey::Migration => MigrationNarration::for($framework),
            default => SharedScript::for($step),
        };
    }

    public static function scope(Framework $framework, StepKey $step): string
    {
        return in_array($step, [StepKey::Install, StepKey::Migration], true)
            ? $framework->value
            : 'umum';
    }

    public static function clip(Framework $framework, StepKey $step, int $index): ?Clip
    {
        $texts = self::for($framework, $step);

        if (! array_key_exists($index, $texts)) {
            return null;
        }

        return new Clip(self::scope($framework, $step), $step->value, $index, $texts[$index]);
    }

    /**
     * @return array<int, Clip>
     */
    public static function briefing(): array
    {
        $clips = [];

        foreach (BriefingScript::texts() as $index => $text) {
            $clips[] = new Clip('umum', BriefingScript::STEP, $index, $text);
        }

        return $clips;
    }
}
