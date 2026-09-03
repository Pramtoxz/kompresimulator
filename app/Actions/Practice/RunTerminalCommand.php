<?php

namespace App\Actions\Practice;

use App\Enums\StepKey;
use App\Guides\ProblemFacts;
use App\Models\Attempt;
use App\Practice\TerminalCommands;

class RunTerminalCommand
{
    /**
     * @return array{ok: bool, command: string, output: string}
     */
    public function handle(Attempt $attempt, StepKey $step, string $typed): array
    {
        $command = $this->normalize($typed);
        $expected = TerminalCommands::for(
            $attempt->problem->framework,
            $step,
            ProblemFacts::from($attempt->problem),
        );

        if ($command === '') {
            return $this->fail('', 'Belum ada yang diketik.');
        }

        foreach ($expected as $entry) {
            if ($entry['command'] === $command) {
                return ['ok' => true, 'command' => $command, 'output' => $entry['output']];
            }
        }

        return $this->fail($command, $this->diagnose($command, $expected));
    }

    /**
     * @param  array<int, array{command: string, output: string}>  $expected
     */
    private function diagnose(string $command, array $expected): string
    {
        foreach ($expected as $entry) {
            if (strcasecmp($entry['command'], $command) === 0) {
                return 'Perintahnya sudah benar, tapi huruf besar kecilnya belum sama persis. Perhatikan huruf kapital pada nama file.';
            }
        }

        foreach ($expected as $entry) {
            if (levenshtein($entry['command'], $command) <= 4) {
                return 'Hampir benar. Ada huruf yang kurang atau kelebihan, baca ulang perintah di kartu lalu ketik sekali lagi.';
            }
        }

        $head = explode(' ', $command)[0];

        if (! in_array($head, ['php', 'composer', 'cd', 'cp', 'copy'], true)) {
            return $head.': command not found';
        }

        return 'Perintah itu tidak dipakai di langkah ini. Ketik perintah yang ada di kartu sebelah.';
    }

    /**
     * @return array{ok: bool, command: string, output: string}
     */
    private function fail(string $command, string $output): array
    {
        return ['ok' => false, 'command' => $command, 'output' => $output];
    }

    private function normalize(string $typed): string
    {
        return trim((string) preg_replace('/\s+/', ' ', trim($typed)));
    }
}
