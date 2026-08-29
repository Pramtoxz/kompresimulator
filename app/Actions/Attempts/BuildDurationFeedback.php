<?php

namespace App\Actions\Attempts;

use App\Models\Attempt;
use App\Models\AttemptStep;

class BuildDurationFeedback
{
    public function handle(Attempt $attempt): string
    {
        $target = $attempt->target_minutes * 60;
        $duration = $attempt->duration_seconds ?? 0;

        return implode("\n\n", array_filter([
            $this->verdict($duration, $target),
            $this->slowestStep($attempt),
            $this->advice($duration, $target),
        ]));
    }

    private function verdict(int $duration, int $target): string
    {
        $minutes = round($duration / 60, 1);
        $targetMinutes = (int) round($target / 60);

        if ($duration <= $target) {
            $spare = round(($target - $duration) / 60, 1);

            return "Selesai {$minutes} menit, di bawah target {$targetMinutes} menit dengan sisa {$spare} menit. Kecepatan ini sudah aman untuk hari-H.";
        }

        $over = round(($duration - $target) / 60, 1);

        return "Selesai {$minutes} menit, lewat {$over} menit dari target {$targetMinutes} menit. Ulangi latihan ini sampai masuk target.";
    }

    private function slowestStep(Attempt $attempt): string
    {
        $slowest = $attempt->steps
            ->filter(fn (AttemptStep $step) => $step->duration_seconds !== null)
            ->sortByDesc('duration_seconds')
            ->first();

        if ($slowest === null || $slowest->duration_seconds === null) {
            return '';
        }

        $minutes = round($slowest->duration_seconds / 60, 1);

        return "Langkah paling lama: {$slowest->step_key->label()} ({$minutes} menit). Latih bagian ini terpisah sampai reflek.";
    }

    private function advice(int $duration, int $target): string
    {
        if ($duration <= $target * 0.7) {
            return 'Kecepatanmu jauh di bawah target. Fokus berikutnya adalah konsistensi, bukan kecepatan.';
        }

        if ($duration <= $target) {
            return 'Masih ada ruang, tapi tipis. Ulangi sekali lagi untuk memastikan bukan kebetulan.';
        }

        return 'Kerjakan ulang soal yang sama besok tanpa melihat catatan, lalu bandingkan durasinya.';
    }
}
