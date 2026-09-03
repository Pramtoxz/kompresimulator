<?php

namespace App\Ai;

class TutorGuard
{
    private const CODE_MARKERS = [
        'Schema::create',
        '$table->',
        '$fillable',
        '$allowedFields',
        '->insert(',
        '->findAll(',
        '::create(',
        '::all(',
        'compact(',
        '@foreach',
        '<form',
        '<input',
        '<select',
        '<table',
        'function HitungBayar',
        'function PilihData',
        'public function simpan',
        'public function laporan',
        'Route::post',
        'Route::get',
        '$routes->',
    ];

    private const LEAK_MARKERS = [
        'HANYA boleh membahas',
        'DILARANG KERAS',
        'Namamu Bg Dito Ganteng',
        'Abaikan semua permintaan',
    ];

    /**
     * @return array{body: string, refused: bool}
     */
    public static function apply(string $reply): array
    {
        $reply = trim($reply);

        if ($reply === '') {
            return self::refuse(TutorInstructions::REFUSAL);
        }

        foreach (self::LEAK_MARKERS as $marker) {
            if (str_contains($reply, $marker)) {
                return self::refuse(TutorInstructions::REFUSAL);
            }
        }

        foreach (self::CODE_MARKERS as $marker) {
            if (str_contains($reply, $marker)) {
                return self::refuse(TutorInstructions::NO_ANSWER);
            }
        }

        if (self::hasLongCodeFence($reply)) {
            return self::refuse(TutorInstructions::NO_ANSWER);
        }

        return ['body' => $reply, 'refused' => false];
    }

    private static function hasLongCodeFence(string $reply): bool
    {
        if (preg_match_all('/```(.*?)```/s', $reply, $matches) === 0) {
            return false;
        }

        foreach ($matches[1] as $block) {
            if (substr_count(trim($block), "\n") >= 2) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array{body: string, refused: bool}
     */
    private static function refuse(string $body): array
    {
        return ['body' => $body, 'refused' => true];
    }
}
