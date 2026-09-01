<?php

return [
    'model' => env('TTS_MODEL', 'gemini-3.1-flash-tts-preview'),

    'voice' => env('TTS_VOICE', 'Kore'),

    'style' => env('TTS_STYLE', 'Bacakan dengan nada guru privat yang sabar, hangat, dan pelan, seolah sedang mendampingi satu murid di sebelahnya'),

    'ffmpeg' => env('TTS_FFMPEG'),

    'bitrate' => env('TTS_BITRATE', '32k'),

    'timeout' => (int) env('TTS_TIMEOUT', 180),

    'directory' => 'audio/panduan',
];
