<?php

return [

    'default' => env('AI_PROVIDER', 'gemini'),

    'providers' => [

        'gemini' => [
            'driver' => 'gemini',
            'key' => env('GEMINI_API_KEY'),
            'url' => env('GEMINI_URL', 'https://generativelanguage.googleapis.com/v1beta/'),
            'models' => [
                'text' => [
                    'default' => env('GEMINI_MODEL', 'gemini-3.5-flash'),
                    'cheapest' => env('GEMINI_MODEL_CHEAPEST', 'gemini-3.5-flash-lite'),
                ],
            ],
        ],

        'anthropic' => [
            'driver' => 'anthropic',
            'key' => env('ANTHROPIC_API_KEY'),
            'url' => env('ANTHROPIC_URL', 'https://api.anthropic.com/v1'),
            'models' => [
                'text' => [
                    'default' => env('ANTHROPIC_MODEL', 'claude-sonnet-5'),
                ],
            ],
        ],

        'deepseek' => [
            'driver' => 'deepseek',
            'key' => env('DEEPSEEK_API_KEY'),
            'models' => [
                'text' => [
                    'default' => env('DEEPSEEK_MODEL', 'deepseek-v4-flash'),
                ],
            ],
        ],

        'router' => [
            'driver' => 'openai-compatible',
            'url' => env('ROUTER_URL'),
            'key' => env('ROUTER_API_KEY'),
            'models' => [
                'text' => [
                    'default' => env('ROUTER_MODEL', 'auto'),
                ],
            ],
        ],

    ],

    'timeout' => (int) env('AI_TIMEOUT', 180),

    'max_tokens' => (int) env('AI_MAX_TOKENS', 8192),

];
