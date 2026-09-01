<?php

namespace App\Console\Commands;

use App\Enums\Framework;
use App\Enums\StepKey;
use App\Guides\ProblemFacts;
use App\Guides\StepCards;
use App\Models\Problem;
use App\Tts\AudioEncoder;
use App\Tts\Clip;
use App\Tts\ClipLibrary;
use App\Tts\GeminiSpeech;
use App\Tts\NarrationScript;
use Illuminate\Console\Command;
use Throwable;

class GenerateNarration extends Command
{
    protected $signature = 'narasi:generate
        {--force : Buat ulang semua klip walau sudah ada}
        {--model= : Pakai model TTS lain, berguna saat kuota harian model utama habis}
        {--prune : Hapus klip yang naskahnya sudah tidak ada lagi}';

    protected $description = 'Membuat berkas suara panduan sekali untuk seluruh project';

    public function handle(GeminiSpeech $speech, AudioEncoder $encoder): int
    {
        if (! $this->countsMatch()) {
            return self::FAILURE;
        }

        $model = $this->option('model');

        if (is_string($model) && $model !== '') {
            config(['tts.model' => $model]);
            $this->warn('Memakai model '.$model.'. Suaranya bisa sedikit berbeda dari klip yang sudah ada.');
        }

        if ($encoder->binary() === null) {
            $this->warn('ffmpeg tidak ditemukan. Klip akan ditulis sebagai WAV yang ukurannya sepuluh kali lebih besar.');
            $this->warn('Isi TTS_FFMPEG di .env dengan lokasi ffmpeg untuk menghasilkan m4a.');
        }

        $manifest = ClipLibrary::manifest();
        $clips = ClipLibrary::all();
        $manifest = $this->prune($clips, $manifest);
        $made = 0;
        $skipped = 0;

        foreach ($clips as $clip) {
            if (! $this->option('force') && $this->isFresh($clip, $manifest)) {
                $skipped++;

                continue;
            }

            try {
                $audio = $speech->synthesize($clip->text);
                $encoder->write($audio, ClipLibrary::path($clip));
            } catch (Throwable $exception) {
                $this->error($clip->key().' gagal: '.$exception->getMessage());

                continue;
            }

            $manifest[$clip->key()] = $clip->hash();
            ClipLibrary::writeManifest($manifest);
            $made++;

            $this->line(sprintf('  %-28s %5.1f detik', $clip->key(), $audio->seconds()));
        }

        $this->newLine();
        $this->info("Selesai: {$made} klip dibuat, {$skipped} dilewati, ".count($clips).' total.');

        return self::SUCCESS;
    }

    /**
     * @param  array<int, Clip>  $clips
     * @param  array<string, string>  $manifest
     * @return array<string, string>
     */
    private function prune(array $clips, array $manifest): array
    {
        $live = array_map(fn (Clip $clip) => $clip->key(), $clips);
        $orphans = array_diff(array_keys($manifest), $live);

        if ($orphans === []) {
            return $manifest;
        }

        if (! $this->option('prune')) {
            $this->warn(count($orphans).' klip sudah tidak dipakai lagi. Jalankan dengan --prune untuk menghapusnya.');

            return $manifest;
        }

        foreach ($orphans as $key) {
            $file = public_path(config('tts.directory').'/'.$key.'.m4a');

            if (is_file($file)) {
                unlink($file);
            }

            unset($manifest[$key]);
            $this->line('  dihapus: '.$key);
        }

        ClipLibrary::writeManifest($manifest);

        return $manifest;
    }

    /**
     * @param  array<string, string>  $manifest
     */
    private function isFresh(Clip $clip, array $manifest): bool
    {
        return ($manifest[$clip->key()] ?? null) === $clip->hash()
            && is_file(ClipLibrary::path($clip));
    }

    private function countsMatch(): bool
    {
        $facts = ProblemFacts::from(new Problem());
        $matched = true;

        foreach (Framework::cases() as $framework) {
            foreach (StepKey::cases() as $step) {
                $cards = count(StepCards::for($step, $framework, $facts));
                $texts = count(NarrationScript::for($framework, $step));

                if ($cards !== $texts) {
                    $matched = false;
                    $this->error("Jumlah narasi tidak cocok pada {$framework->value} langkah {$step->value}: {$cards} kartu, {$texts} narasi.");
                }
            }
        }

        return $matched;
    }
}
